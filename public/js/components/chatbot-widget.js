


//   WELL THIS DOESNT WORK DOES IT
//       _==/          i     i          \==
//     /XX/            |\___/|            \XX\
//   /XXXX\            |XXXXX|            /XXXX\
//  |XXXXXX\_         _XXXXXXX_         _/XXXXXX|
// XXXXXXXXXXXxxxxxxxXXXXXXXXXXXxxxxxxxXXXXXXXXXXX
// |XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX|
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// |XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX|
// XXXXXX/^^^^"\XXXXXXXXXXXXXXXXXXXXX/^^^^^\XXXXXX
//  |XXX|       \XXX/^^\XXXXX/^^\XXX/       |XXX|
//    \XX\       \X/    \XXX/    \X/       /XX/
//       "\       "      \X/      "       /"
//                        !


export class ChatBot {
  // The chatbot attaches to a single DOM container, and builds the chat within there
  // All we need in the constructor is ID of the DOM container
  /* Example of prior chatbot DOM
    <div id="chat">
      <input id="chat0" type="text" class="form-control" placeholder="Start typing if you need help" onchange="chat1()">
      <div id="chat1" input type="text" onchange="chat2()"></div>
      <div id="chat2" input type="text" onchange="chat3()"></div>
      <div id="chat3" input type="text" onchange="chat4()"></div>
      <div id="chat4" input type="text" oninput="qrcode()"></div>
    </div>
  */
  // So the widget would take a string named "chat" (already on the page), and would build chat-0, chat-1, etc;
  constructor(idOfDom, onDoneChat) {
    this.idOfDom = idOfDom;
    this.onDoneChat = onDoneChat;
    this.maxIdx = 3; // hardcoded for now

    this.chatRecursive(0, "HelpBot: Hello, how can I help?");
  }

  chatSleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  chatParagraph(idx, text) {
    let para = document.createElement("p");
    para.classList.add('mt-2');
    para.innerHTML = text;
    return para;
  }

  chatInput(idx) {
    let input = document.createElement('input');
    input.classList.add('form-control');
    input.setAttribute('type', 'text');
    input.id = this.idOfDom + "-" + idx;
    input.addEventListener('change', (event) => {
      this.chatRecursive(idx + 1, this.switchParaText(idx + 1));
    });
    return input;
  }

  switchParaText(idx) {
    let text = "";
    switch (idx) {
      case 1:
        text = "HelpBot: OK let me help you with that. Can you please let me know your name and email?";
        break;
      case 2:
        text = "HelpBot: Thank you. I'll save your basket and give you a QR Code to pay for the items later. Is that ok?";
        break;
      case 3:
        text = "HelpBot: No problem, Here's a QR Code. If you would also like the link for later, please hit send email";
        break;
      default:
        break;
    }

    return text;
  }

  async chatRecursive(idx, paraText) {
    // The chatbot is over
    if (idx >= this.maxIdx) {
      return this.endChatbot(idx);
    } else {
      await this.chatSleep(1000);
      let para = this.chatParagraph(idx, paraText);
      document.getElementById(this.idOfDom).appendChild(para);
      let input = this.chatInput(idx);
      document.getElementById(this.idOfDom).appendChild(input);
    }
  }

  async endChatbot(idx) {
    await this.chatSleep(1000);
    let para = this.chatParagraph(idx, "HelpBot: No problem, Here's a QR Code. If you would also like the link for later, please hit send email");
    document.getElementById(this.idOfDom).appendChild(para);
    await this.chatSleep(3000);
    this.onDoneChat();
  }
}
