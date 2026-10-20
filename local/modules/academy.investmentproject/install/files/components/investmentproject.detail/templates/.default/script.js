(function () {
    /**
     * @namespace BX.Academy.InvestmentProject.Detail
     */
    BX.namespace('Academy.InvestmentProject.Detail');

    BX.Academy.InvestmentProject.Detail.Manager = class {
        bindEvents() {
            BX.Event.EventEmitter.subscribe(
                'BX.UI.EntityEditorUser:openSelector',
                (event) => {
                    const data = event.data[1];
                    const dialog = new BX.UI.EntitySelector.Dialog({
                        targetNode: data.anchor,
                        enableSearch: true,
                        multiple: false,
                        context: 'INVESTMENT_PROJECT',
                        entities: [
                            {
                                id: 'user'
                            }
                        ],
                        events: {
                            'Item:OnSelect': (onSelectEvent) => {
                                const selectedItem = onSelectEvent.data.item;
                                data.callback(dialog, {
                                    entityId: selectedItem.id,
                                    avatar: selectedItem.avatar,
                                    name: selectedItem.title.text
                                })
                            }
                        }
                    });
                    dialog.show();
                }
            );
        }
    }
})();