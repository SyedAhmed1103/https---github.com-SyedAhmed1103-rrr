 window.addEventListener("DOMContentLoaded", () => {
	const burger = document.querySelector(".header__burger");
	const navWrapper = document.querySelector(".header__navigation-wrapper");

	if (burger && navWrapper) {
		burger.addEventListener("click", () => {
			burger.classList.toggle("active");
			navWrapper.classList.toggle("open");
		});
	}

	document.querySelectorAll(".header__list-item.has-submenu").forEach((item) => {
		item.addEventListener("click", () => {
			if (window.innerWidth > 1400) return;

			item.classList.toggle("active");

			const submenuWrapper = item.querySelector(".submenu-wrapper");

			if (submenuWrapper.style.maxHeight) {
				submenuWrapper.style.maxHeight = null;
			} else {
				submenuWrapper.style.maxHeight = `${submenuWrapper.scrollHeight}px`;
			}
		});
	});

	window.addEventListener("resize", () => {
		if (window.innerWidth <= 1025) {
			document
				.querySelectorAll(".header__list-item.has-submenu.active")
				.forEach((item) => {
					const submenuWrapper = item.querySelector(".submenu-wrapper");

					if (submenuWrapper) {
						submenuWrapper.style.maxHeight = `${submenuWrapper.scrollHeight}px`;
					}
				});
		} else {
			document.querySelectorAll(".submenu-wrapper").forEach((wrapper) => {
				wrapper.style.maxHeight = null;
			});
		}
	});

	document.querySelectorAll('a[href="#"]').forEach((link) => {
		link.addEventListener("click", (e) => {
			e.preventDefault();
		});
	});

	const submenuWrappers = document.querySelectorAll(".submenu-wrapper");

	submenuWrappers.forEach((submenuWrapper) => {
		const submenuItems = submenuWrapper.querySelectorAll(
			".submenu-list__item.has-submenu"
		);
		const defaultActiveItem = submenuWrapper.querySelector(
			".submenu-list__item.has-submenu.active"
		);

		let returnTimeout;

		submenuItems.forEach((item) => {
			item.addEventListener("mouseenter", () => {
				clearTimeout(returnTimeout);

				submenuItems.forEach((i) => i.classList.remove("active"));

				item.classList.add("active");
			});
		});

		submenuWrapper.addEventListener("mouseleave", () => {
			submenuItems.forEach((i) => i.classList.remove("active"));

			returnTimeout = setTimeout(() => {
				if (defaultActiveItem) {
					defaultActiveItem.classList.add("active");
				}
			}, 300);
		});
	});
});