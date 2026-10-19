BX.ready(function () {
    // Если уже в iframe слайдера, не выполняем никаких действий
    if (window.top !== window) {
        return;
    }

    BX.SidePanel.Instance.open(window.location.href, {
        width: 1200,
        cacheable: false
    });
});
