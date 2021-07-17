window.addEventListener('DOMContentLoaded', (e) => {
	let loggedIn = document.querySelector('body').classList.contains('logged-in') ? true : false;
	if(loggedIn) {

		// When user will click on add to wishlist button.
		if(document.querySelector('.wishlist-toggle')){
			document.querySelector('.wishlist-toggle').addEventListener('click', (e) => {
				e.preventDefault();
				let productId = e.target.getAttribute("data-product");
				
				let formData = new FormData();
				formData.append('action', 'sbwl_add_to_wishlist');
				formData.append('user_id', opts.userId);
				formData.append('product_id', productId);
				fetch(opts.ajaxUrl, {
					method: 'POST',
					body: formData
				}).then(function (response) {
					if(response) {
						console.log('working');
					}
				});
			});
		}

		// After add to wishlist button is clicked.
		if(document.querySelector('.wishlist-toggle')){
			document.querySelector('.wishlist-toggle').addEventListener('mouseup',function() {
				e.preventDefault();
				document.querySelector('.wl-new-added').classList.remove('wwl-hide');
			});
		}

		// When user will click add on remove from wishlist button/icon.
		if(document.querySelector('.wwl-rfc-btn')){
			let wowlBtns = document.querySelectorAll('.wwl-rfc-btn');
			for(var i = 0; i<wowlBtns.length; i++) {
				e.preventDefault();
				let productId = e.target.getAttribute("data-product");
				
				let formData = new FormData();
				formData.append('action', 'sbwl_remove_from_wishlist');
				formData.append('user_id', opts.userId);
				formData.append('product_id', productId);
				fetch(opts.ajaxUrl, {
					method: 'POST',
					body: formData
				}).then(function (response) {
					if(response) {
						document.getElementById(productId).innerHTML = '';
					}
				});
			}
		}
	}
	
});