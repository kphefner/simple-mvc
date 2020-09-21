	<style type="text/css">
		#login_wrapper {
			max-width: 600px;
			margin:0 auto;
			background: white;
			padding:1rem;
		}
		#login_msg {
			color:red;
			border:solid 1px gray;
			padding:1rem;
		}
	</style>
	<section id="login_wrapper">
		<div id="login_msg" class="mb-3" aria-live="assertive" style="display:none;">
		</div>
		<form id="login" method="post" action="/login/ajaxDoLogin">
			<div>
				<label for="username">Email Address ( <span class="reqind">required</span> ) </label>
				<input type="email" class="form-control" name="username" id="username" placeholder="user@example.com" required aria-required="true" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$">
			</div>
			<div class="mt-3">
				<label for="password">Password: ( <span class="reqind">required</span> ) </label>
				<input type="password" class="form-control" name="password" id="password" placeholder="Password" required aria-required="true">
			</div>
			<div class="text-right">
				<button type="submit" id="login_btn" class="btn btn-primary mt-4" tabindex="0">
					<span>LOGIN</span>
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
				</button>
			</div>
		</form>
	</section>
	<script type="text/javascript">
		var loginForm = document.querySelector("#login");
		var loginBtn = document.querySelector("#login_btn");
		
		const getCookie = (name) => {
			var value = "; " + document.cookie;
			var parts = value.split("; " + name + "=");
			if (parts.length == 2) return parts.pop().split(";").shift();
		}
		
		const processForm = (event) => {
			event.preventDefault();
			loginBtn.classList.add('show-spinner');
			// HERE an ajax API call would be made to the controller that would in turn connect to the model/api
			postAjax('/login/ajaxDoLogin',{"username":"testing@email.com","password":"somepassword"},function(data){
				loginBtn.classList.remove('show-spinner');
				var json = JSON.parse(data);
				if(json.success === false) {
					let msgDiv = document.getElementById("login_msg");
					msgDiv.innerHTML = json.msg;
					msgDiv.style.display = 'block';
					console.log(getCookie('portalPageRequest'));
				} else {
					// redirect to private resource... user should be in session
					window.location = "/myAccount/index";
				}
			});
			return false;
		}
		const postAjax = (url,data,success) => {
			var params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

	    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	    xhr.open('POST', url);
	    xhr.onreadystatechange = function() {
	        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
	    };
	    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	    xhr.send(params);
	    return xhr;
		}
		if (loginForm.attachEvent) {
			loginForm.attachEvent("submit", processForm);
		} else {
			loginForm.addEventListener("submit", processForm);
		}
	</script>