import mediumZoom from "medium-zoom";
import { Dropdown } from "./dropdown";
import { OffCanvas } from "./off-canvas";
import { domReady } from "./utils";

domReady(() => {
    mediumZoom('.wp-block-image > img');

    // Let the document know when the keyboard is being used
    document.body.addEventListener('mousedown', _ => {
        document.body.classList.remove('focus-keyboard');
    });
    document.body.addEventListener('keydown', event => {
        if (event.key === 'Tab') {
            document.body.classList.add('focus-keyboard');
        }
    });

    // Responsive iframes
    document.querySelectorAll('iframe').forEach(iframe => {
        if (!iframe.height || !iframe.width) return;

        const container = iframe.parentNode;

        if (!container.classList.contains('wp-block-embed__wrapper')) return;

        container.classList.add('responsive-iframe');
        container.style.paddingTop = `${iframe.height / iframe.width * 100}%`;
    });

    // Mobile navigation
    const leftSidebar = document.querySelector('#site-sidebar');

    if (leftSidebar) {
        new OffCanvas(leftSidebar);
    }

    // Right sidebar
    const rightSidebar = document.querySelector('#site-sidebar-right');

    if (rightSidebar) {
        function updateRightSidebar() {
            const isThreeColumns = document.body.classList.contains('layout--has-lsidebar') && document.body.classList.contains('layout--has-rsidebar');

            rightSidebar.classList.remove('widget-area--vertical', 'widget-area--horizontal');
            rightSidebar.classList.add((isThreeColumns && window.innerWidth < 1366) || window.innerWidth < 992
                ? 'widget-area--horizontal'
                : 'widget-area--vertical');
        }

        window.addEventListener('resize', updateRightSidebar);
        updateRightSidebar();
    }

    // Dropdown menu
    document.querySelectorAll('.primary-menu li').forEach(element => new Dropdown(element));
});