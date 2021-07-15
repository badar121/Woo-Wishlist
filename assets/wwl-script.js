window.addEventListener('DOMContentLoaded', (e) => {
	let loggedIn = document.querySelector('body').classList.contains('logged-in') ? true : false;

	if(document.querySelector('.wishlist-toggle')){
		document.querySelector('.wishlist-toggle').addEventListener('click', (e) => {
			e.preventDefault();
			let productId = e.target.getAttribute("data-product");
			
			if(loggedIn) {
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
			}
		});
	}

	if(document.querySelector('.wishlist-toggle')){
		document.querySelector('.wishlist-toggle').addEventListener('mouseup',function() {
			e.preventDefault();
			document.querySelector('.wl-new-added').classList.remove('wwl-hide');
		});
	}

	if(document.querySelector('.wwl-rfc-btn')){
		document.querySelector('.wwl-rfc-btn').addEventListener('click', (e) => {
			e.preventDefault();
			let productId = e.target.getAttribute("data-product");
			
			if(loggedIn) {
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
		});
	}
	
});