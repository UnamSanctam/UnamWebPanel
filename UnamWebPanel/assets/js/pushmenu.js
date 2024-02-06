const NAME = 'PushMenu', DATA_KEY = 'pushmenu', EVENT_KEY = `.${DATA_KEY}`,
    EVENT_COLLAPSED = `collapsed${EVENT_KEY}`, EVENT_SHOWN = `shown${EVENT_KEY}`,
    SELECTOR_TOGGLE = '[data-widget="pushmenu"]', SELECTOR_BODY = 'body',
    SELECTOR_OVERLAY = '#sidebar-overlay', SELECTOR_WRAPPER = '.wrapper',
    CLASS_COLLAPSED = 'sidebar-collapse', CLASS_OPEN = 'sidebar-open', CLASS_CLOSED = 'sidebar-closed',
    Default = { autoCollapseSize: 992, enableRemember: true };

class PushMenu {
    constructor(element, options) {
        this._element = element;
        this._options = $.extend({}, Default, options);
        if (!$(SELECTOR_OVERLAY).length) $('<div />', {id: 'sidebar-overlay'}).on('click', this.collapse.bind(this)).appendTo(SELECTOR_WRAPPER);
        this.remember();
        $(window).resize(() => this.autoCollapse(true));
    }

    expand() {
        $(SELECTOR_BODY).addClass(CLASS_OPEN).removeClass(`${CLASS_COLLAPSED} ${CLASS_CLOSED}`);
        if (this._options.enableRemember) localStorage.setItem(`remember${EVENT_KEY}`, CLASS_OPEN);
        $(this._element).trigger($.Event(EVENT_SHOWN));
    }

    collapse() {
        $(SELECTOR_BODY).removeClass(CLASS_OPEN).addClass(`${CLASS_COLLAPSED} ${CLASS_CLOSED}`);
        if (this._options.enableRemember) localStorage.setItem(`remember${EVENT_KEY}`, CLASS_COLLAPSED);
        $(this._element).trigger($.Event(EVENT_COLLAPSED));
    }

    toggle() {
        $(SELECTOR_BODY).hasClass(CLASS_COLLAPSED) ? this.expand() : this.collapse();
    }

    autoCollapse(resize = false) {
        if ($(window).width() <= this._options.autoCollapseSize || resize) this.toggle();
    }

    remember() {
        $(window).width() <= this._options.autoCollapseSize || localStorage.getItem(`remember${EVENT_KEY}`) === CLASS_COLLAPSED ? this.collapse() : this.expand();
    }

    static _jQueryInterface(operation) {
        return this.each(function() {
            let data = $(this).data(DATA_KEY) || new PushMenu(this, $.extend({}, Default, $(this).data()));
            if (typeof operation === 'string' && data[operation]) data[operation]();
        });
    }
}

$(document).on('click', SELECTOR_TOGGLE, e => {
    e.preventDefault();
    PushMenu._jQueryInterface.call($(e.currentTarget).closest(SELECTOR_TOGGLE), 'toggle');
});

$(window).on('load', () => PushMenu._jQueryInterface.call($(SELECTOR_TOGGLE), 'remember'));

$.fn[NAME] = PushMenu._jQueryInterface;
$.fn[NAME].Constructor = PushMenu;