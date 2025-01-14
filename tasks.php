<?php
// tasks.php
require_once 'functions.php';
$pdo = db_conn();

check_login();

// カテゴリーの追加
if (isset($_POST['new_category'])) {
    $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['new_category']]);
}

// タスクの処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && !empty($_POST['action'])) {
        // 最大の表示順を取得
        $stmt = $pdo->prepare("SELECT COALESCE(MAX(display_order), 0) + 1 AS next_order FROM next_actions WHERE user_id = ?");
        $stmt->execute([$_SESSION['email']]);
        $nextOrder = $stmt->fetch(PDO::FETCH_ASSOC)['next_order'];
        
        // 新規タスクの追加
        $stmt = $pdo->prepare("INSERT INTO next_actions (user_id, category_id, action, priority, due_date, display_order) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['email'],
            $_POST['category_id'] ?: null,
            $_POST['action'],
            $_POST['priority'],
            $_POST['due_date'] ?: null,
            $nextOrder
        ]);
    } elseif (isset($_POST['complete_id'])) {
        // タスクの完了
        $stmt = $pdo->prepare("UPDATE next_actions SET status = 'completed' WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['complete_id'], $_SESSION['email']]);
    } elseif (isset($_POST['update_id'])) {
        // タスクの更新
        $stmt = $pdo->prepare("UPDATE next_actions 
                              SET action = ?, category_id = ?, priority = ?, due_date = ? 
                              WHERE id = ? AND user_id = ?");
        $stmt->execute([
            $_POST['updated_action'],
            $_POST['category_id'] ?: null,
            $_POST['priority'],
            $_POST['due_date'] ?: null,
            $_POST['update_id'],
            $_SESSION['id']
        ]);
    } elseif (isset($_POST['reorder'])) {
        // タスクの並び替え
        $orders = json_decode($_POST['order'], true);
        foreach ($orders as $order => $id) {
            $stmt = $pdo->prepare("UPDATE next_actions SET display_order = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$order, $id, $_SESSION['email']]);
        }
    }
    
    if (!isset($_POST['reorder'])) {  // 並び替え以外の場合にリダイレクト
        header('Location: ' . $_SERVER['PHP_SELF'] . (isset($_GET['show_completed']) ? '?show_completed=1' : ''));
        exit;
    }
}

include 'header.php';

// カテゴリー一覧の取得
$stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY name");
$stmt->execute([$_SESSION['email']]);
$categories = $stmt->fetchAll();
?>

<h1>ネクストアクション</h1>

<!-- カテゴリー追加フォーム -->
<form method="POST" action="" class="mb-4">
    <input type="text" name="new_category" placeholder="新しいカテゴリーを追加..." required>
    <button type="submit">カテゴリー追加</button>
</form>

<!-- タスク追加フォーム -->
<form method="POST" action="" class="mb-4">
    <input type="text" name="action" placeholder="新しいアクションを追加..." required>
    <select name="category_id">
        <option value="">カテゴリーなし</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['email']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <select name="priority">
        <option value="1">優先度: 高</option>
        <option value="2">優先度: 中</option>
        <option value="3" selected>優先度: 低</option>
    </select>
    <input type="date" name="due_date">
    <button type="submit">追加</button>
</form>

<!-- フィルターとソート -->
<div class="mb-4">
    <a href="?status=active" class="btn <?php echo (!isset($_GET['status']) || $_GET['status'] === 'active') ? 'active' : ''; ?>">
        進行中のタスク
    </a>
    <a href="?status=completed" class="btn <?php echo (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'active' : ''; ?>">
        完了したタスク
    </a>
</div>

<select id="sort-select" onchange="sortTasks(this.value)">
    <option value="order">手動順</option>
    <option value="priority">優先度順</option>
    <option value="due_date">締切日順</option>
</select>

<ul id="task-list" class="sortable">
    <?php
$status = (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'completed' : 'active';
    $stmt = $pdo->prepare(
        "SELECT na.*, c.name as category_name 
         FROM next_actions na 
         LEFT JOIN categories c ON na.category_id = c.id 
         WHERE na.user_id = ? AND na.status = ? 
         ORDER BY na.display_order ASC"
    );
    $stmt->execute([$_SESSION['email'], $status]);
    $tasks = $stmt->fetchAll();
    
    foreach ($tasks as $task):
        $priorityClass = 'priority-' . $task['priority'];
        $dueSoon = $task['due_date'] && strtotime($task['due_date']) - time() < 7 * 24 * 60 * 60;
    ?>
        <li id="task-<?php echo $task['email']; ?>" class="task-item <?php echo $priorityClass; ?>" data-id="<?php echo $task['id']; ?>">
            <div class="task-content">
                <span class="task-text" onclick="showEditForm(<?php echo $task['email']; ?>)">
                    <?php echo htmlspecialchars($task['action']); ?>
                </span>
                
                <?php if ($task['category_name']): ?>
                    <span class="category-tag"><?php echo htmlspecialchars($task['category_name']); ?></span>
                <?php endif; ?>
                
                <?php if ($task['due_date']): ?>
                    <span class="due-date <?php echo $dueSoon ? 'due-soon' : ''; ?>">
                        期限: <?php echo $task['due_date']; ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- 編集フォーム -->
            <form method="POST" action="" class="edit-form" id="edit-form-<?php echo $task['email']; ?>" style="display: none;">
                <input type="hidden" name="update_id" value="<?php echo $task['id']; ?>">
                <input type="text" name="updated_action" value="<?php echo htmlspecialchars($task['action']); ?>">
                <select name="category_id">
                    <option value="">カテゴリーなし</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['email']; ?>" <?php echo $task['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="priority">
                    <option value="1" <?php echo $task['priority'] == 1 ? 'selected' : ''; ?>>優先度: 高</option>
                    <option value="2" <?php echo $task['priority'] == 2 ? 'selected' : ''; ?>>優先度: 中</option>
                    <option value="3" <?php echo $task['priority'] == 3 ? 'selected' : ''; ?>>優先度: 低</option>
                </select>
                <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>">
                <button type="submit">更新</button>
                <button type="button" onclick="hideEditForm(<?php echo $task['email']; ?>)">キャンセル</button>
            </form>
            
            <!-- 完了/復元フォーム -->
            <form method="POST" action="" style="display: inline;">
                <input type="hidden" name="complete_id" value="<?php echo $task['email']; ?>">
                <button type="submit">
                    <?php echo $status == 'active' ? '完了' : '復元'; ?>
                </button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<link rel="stylesheet" href="css/styles.css">
<?php include 'footer.php'; ?>
