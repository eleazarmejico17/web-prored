// Tabs compartidos (Internet / IPTV / Dúo) — usado por index y planes
document.addEventListener('DOMContentLoaded', function () {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    if (tabBtns.length && tabPanes.length) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                tabPanes.forEach(pane => pane.classList.remove('active'));
                const targetId = 'tab' + this.dataset.tab.charAt(0).toUpperCase() + this.dataset.tab.slice(1);
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });
    }
});
