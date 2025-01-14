// main.js

document.addEventListener("DOMContentLoaded", function () {
    const chatForm = document.getElementById("chat-form");
    const chatInput = document.getElementById("chat-input");
    const chatMessages = document.getElementById("chat-messages");
  
      // HTML内に埋め込まれたメールアドレスを取得
  const userEmail = document.getElementById("chat-form").dataset.email;
  
    console.log(chatInput);
    chatForm.addEventListener("submit", async function (event) {
      event.preventDefault();
      const message = chatInput.value.trim();
      if (message === "") return;
  
      addMessageToChat(message, "user");
      chatInput.value = "";
  
      try {
        const response = await fetch("api-request.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `ajax=1&message=${encodeURIComponent(message)}`,
        });
  
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = "";
        let currentMessage = "";
  
        while (true) {
          const { value, done } = await reader.read();
          if (done) break;
  
          buffer += decoder.decode(value, { stream: true });
  
          // データチャンクを処理
          const lines = buffer.split("\n");
          buffer = lines.pop() || ""; // 最後の不完全なラインを保持
  
          for (const line of lines) {
            if (line.startsWith("data: ")) {
              try {
                const data = JSON.parse(line.slice(6));
  
                if (data.event === "message" && data.answer) {
                  currentMessage += data.answer;
                  // 既存のメッセージを更新
                  updateOrCreateMessage(currentMessage, "bot");
                } else if (data.event === "message_end") {
                  // ストリーミング完了
                  console.log("Stream completed", data.metadata);
                }
              } catch (e) {
                console.log("Parsing error:", e);
              }
            }
          }
        }
      } catch (error) {
        console.error("Error:", error);
        addMessageToChat("通信エラーが発生しました。", "bot");
      }
    });
  
    function updateOrCreateMessage(message, userType) {
      const lastMessage = document.querySelector(
        "#chat-messages .bot:last-child"
      );
      if (lastMessage) {
        lastMessage.textContent = message;
      } else {
        addMessageToChat(message, userType);
      }
    }
  
    function addMessageToChat(message, userType) {
      const messageElement = document.createElement("div");
      messageElement.className = `message ${userType}`;
      messageElement.textContent = message;
      chatMessages.appendChild(messageElement);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  });