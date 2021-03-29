


//   WELL THIS DOESNT WORK DOES IT
      _                         _
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


export class ChatBot() {
//export class ChatBot {
  // Pass all data needed for the chatbot API to set in the constructor
//   constructor(data) {
//    this.data = data;
// }

   function sleep(ms) {
     return new Promise(resolve => setTimeout(resolve, ms));
   }

   async function chat1() {
    await sleep(1000);
    var para = document.createElement("P");
    para.innerHTML = "HelpBot: Hello, how can I help?";
    document.getElementById("chat1").appendChild(para);
    var input1 = document.createElement('input');
    input1.setAttribute('type', 'text');
    input1.placeholder="e.g. Save my basket";
    input1.setAttribute('class', 'chat');
    document.getElementById("chat1").appendChild(input1);
  }

  async function chat2() {
    await sleep(1000);
    var para = document.createElement("P");
    para.innerHTML = "HelpBot: OK let me help you with that. Can you please let me know your name and email?";
    document.getElementById("chat2").appendChild(para);
    var input2 = document.createElement('input');
    input2.setAttribute('type', 'text');
    input2.setAttribute('class', 'chat');
    input2.setAttribute('name', 'email');
    document.getElementById("chat2").appendChild(input2).value;
  }

  async function chat3() {
    await sleep(1000);
    var para = document.createElement("P");
    para.innerHTML = "HelpBot: Thank you. I'll save your basket and give you a QR Code to pay for the items later. Is that ok?";
    document.getElementById("chat3").appendChild(para);
    var input3 = document.createElement('input');
    input3.setAttribute('type', 'text');
    input3.setAttribute('class', 'chat');
    document.getElementById("chat3").appendChild(input3);
  }

  async function chat4() {
    await sleep(1000);
    var para = document.createElement("P");
    para.innerHTML = "HelpBot: No problem, Here's a QR Code. If you would also like the link for later, please hit send email";
    document.getElementById("chat4").appendChild(para);
    await sleep(2000);
    var para = document.createElement("P");
    para.setAttribute('type', 'url');
    para.innerHTML ="Please scan. Thank you.";
    document.getElementById("chat4").appendChild(para);
    await sleep(3000);

  generateQrCode();
  function generateQrCode() {
    $('#qr-code').empty();
    $('#choose-terminal').hide();
    $('#success-or-failure').hide();
    newPbl.getQRCode().then(function(qrCodeSvg) {
      $('#qr-code').append(qrCodeSvg);
      $('#qr-code').show();
      $('#chat-modal').modal('hide');
      $('#action-modal').modal('show');
    });
    }
  }
}
