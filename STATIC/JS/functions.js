var cartModal = document.getElementById("myCart");
var productModal = document.getElementById("addProduct");
var profileModal = document.getElementById("updateProfile");
var productManageModal = document.getElementById("manageProduct");
var ordersModal = document.getElementById("myOrders");
// Get the <span> element that closes the modal

// When the user clicks on the button, open the modal
function showCartModal() {
	cartModal.style.display = "block";
}
function showProductModal() {
	productModal.style.display = "block";
}
function showProfileModal() {
	profileModal.style.display = "block";
}
function showManageProductModal(){
	productManageModal.style.display = "block";
}
function showOrderModal(){
	ordersModal.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
	if (event.target == cartModal) {
		removeModal("cart");
	}
	if (event.target == productModal) {
		removeModal("product");
	}
	if (event.target == profileModal) {
		removeModal("profile");
	}
	if (event.target == productManageModal) {
		removeModal("manageProduct");
	}
	if (event.target == ordersModal) {
		removeModal("myOrders");
	}
};

function removeModal(msg) {
	switch (msg) {
		case "product":
			productModal.classList.add("exit");
			setTimeout(() => {
				productModal.classList.remove("exit");
				productModal.style.display = "none";
			}, 200);
			break;
		case "cart":
			cartModal.classList.add("exit");
			setTimeout(() => {
				cartModal.classList.remove("exit");
				cartModal.style.display = "none";
			}, 200);
			break;
		case "profile":
			profileModal.classList.add("exit");
			setTimeout(() => {
				profileModal.classList.remove("exit");
				profileModal.style.display = "none";
			}, 200);
			break;
		case "manageProduct":
			productManageModal.classList.add("exit");
			setTimeout(() => {
				productManageModal.classList.remove("exit");
				productManageModal.style.display = "none";
			}, 200);
			break;
		case "myOrders":
			ordersModal.classList.add("exit");
			setTimeout(() => {
				ordersModal.classList.remove("exit");
				ordersModal.style.display = "none";
			}, 200);
			break;
		default:
			break;
	}
}

setTimeout(function () {
	var toasts = document.getElementsByClassName("toast");
	for (var i = 0; i < toasts.length; i++) {
		toasts[i].classList.add("hide");
	}
}, 5000);

setTimeout(function () {
	var toasts = document.getElementsByClassName("toast");
	for (var i = 0; i < toasts.length; i++) {
		toasts[i].style.display = "none";
	}
}, 5500);

document
	.getElementById("dropdownButtonSearch")
	.addEventListener("click", function () {
		var searchDropdown = document.getElementById("Search");
		searchDropdown.style.display =
			searchDropdown.style.display === "block" ? "none" : "block";
	});


const mainImageInput = document.getElementById("mainImageInput");
mainImageInput.addEventListener("change", handleImageChange);
function handleImageChange(event) {
	const input = event.target;
	const previewDiv = document.getElementById("mainImagePreview");

	if (input.files && input.files[0]) {
		const reader = new FileReader();
		reader.onload = function (e) {
			const img = document.createElement("img");
			img.src = e.target.result;
			img.alt = input.files[0].name;
			previewDiv.innerHTML = "";
			previewDiv.appendChild(img);
		};
		reader.readAsDataURL(input.files[0]);
	}
}

document.addEventListener("DOMContentLoaded", function () {
	const slider = document.getElementById("min-price-slider");
	const output = document.getElementById("min-price-output");

	slider.addEventListener("input", function () {
		output.value = this.value;
	});

	output.addEventListener("change", function () {
		slider.value = this.value;
	});
});

function delayedTrigger() {
	document.getElementById("Search").style.display = "block";
}

const buttonClass = "dropdownButtonSearch0";

document.querySelectorAll(buttonClass).forEach((btn) => {
	btn.addEventListener("click", function () {
		// Run the function after 2 seconds
		setTimeout(delayedTrigger, 2000);
	});
});

