// demoSession global variable that is always available containing demo settings

window.onload = function(){
  document.getElementById('merchantName').innerHTML = demoSession ? demoSession.merchantName : '';
};
