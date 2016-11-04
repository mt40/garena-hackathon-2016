//var user_list = JSON.parse(user_list);

function userExists(username, password) {
  if(!username || !password)
    return false;
  for(let i = 0; i < user_list.length; ++i) {
    let u = user_list[i];
    if(u.username === username && u.password === password)
      return true;
    return false;
  }
}

$(document).ready(function(){
  $(".btn-login").click(function(){
    let username = $("#ip_un").val();
    let password = $("#ip_pw").val();
    
    if(userExists(username, password)) {
      // Save info in cookie
      // ( To read cookie, use Cookies.get('key'); )
      Cookies.set('username', username, { expires: 1 });
      window.location.replace("index.html");
    }
    else {
      alert("Nice try ( ͡° ͜ʖ ͡°)");
    }
  });
});