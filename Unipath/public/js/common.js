const menuBtn = document.getElementById('menuBtn');
const mobileMenu = document.getElementById('mobileMenu');
const menuOpenIcon = document.getElementById('menuOpenIcon');
const menuCloseIcon = document.getElementById('menuCloseIcon');

const toolsBtn = document.getElementById('toolsBtn');
const toolsMenu = document.getElementById('toolsMenu');
const toolsArrow = document.getElementById('toolsArrow');

let isMenuOpen = false;
let isToolsOpen = false;

if (menuBtn && mobileMenu && menuOpenIcon && menuCloseIcon) {
    menuBtn.addEventListener('click', () => {
        isMenuOpen = !isMenuOpen;

        if (isMenuOpen) {
            mobileMenu.classList.remove('max-h-0', 'opacity-0');
            mobileMenu.classList.add('max-h-[500px]', 'opacity-100');

            menuOpenIcon.classList.remove('opacity-100', 'rotate-0', 'scale-100');
            menuOpenIcon.classList.add('opacity-0', 'rotate-90', 'scale-75');

            menuCloseIcon.classList.remove('opacity-0', 'rotate-90', 'scale-75');
            menuCloseIcon.classList.add('opacity-100', 'rotate-0', 'scale-100');
        } else {
            mobileMenu.classList.remove('max-h-[500px]', 'opacity-100');
            mobileMenu.classList.add('max-h-0', 'opacity-0');

            menuOpenIcon.classList.remove('opacity-0', 'rotate-90', 'scale-75');
            menuOpenIcon.classList.add('opacity-100', 'rotate-0', 'scale-100');

            menuCloseIcon.classList.remove('opacity-100', 'rotate-0', 'scale-100');
            menuCloseIcon.classList.add('opacity-0', 'rotate-90', 'scale-75');

            // close tools submenu too
            toolsMenu.classList.remove('max-h-[300px]', 'opacity-100');
            toolsMenu.classList.add('max-h-0', 'opacity-0');
            toolsArrow.classList.remove('rotate-180');
            isToolsOpen = false;
        }
    });
}

if (toolsBtn && toolsMenu && toolsArrow) {
    toolsBtn.addEventListener('click', () => {
        isToolsOpen = !isToolsOpen;

        if (isToolsOpen) {
            toolsMenu.classList.remove('max-h-0', 'opacity-0');
            toolsMenu.classList.add('max-h-[300px]', 'opacity-100', 'mt-3');
            toolsArrow.classList.add('rotate-180');
        } else {
            toolsMenu.classList.remove('max-h-[300px]', 'opacity-100', 'mt-3');
            toolsMenu.classList.add('max-h-0', 'opacity-0');
            toolsArrow.classList.remove('rotate-180');
        }
    });
}