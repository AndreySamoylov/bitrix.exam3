(function () {
    /**
     * @namespace BX.Academy.InvestmentProject.Grid
     */

    BX.namespace('Academy.InvestmentProject.Grid');

    BX.Academy.InvestmentProject.Grid.Manager = class Manager {
        static #instance = null;

        constructor({gridId, componentName, deleteProjectAction}) {
            if (!BX.Type.isStringFilled(gridId)) {
                throw 'Missing grid id';
            }
            if (!BX.Type.isStringFilled(componentName)) {
                throw 'Missing component name';
            }
            if (!BX.Type.isStringFilled(deleteProjectAction)) {
                throw 'Missing action name for "delete project" action';
            }

            const grid = BX.Main.gridManager.getInstanceById(gridId);
            if (!grid) {
                throw `Grid with id ${gridId} not found`;
            }

            this.grid = grid;
            this.componentName = componentName;
            this.deleteProjectAction = deleteProjectAction;
        }

        static getInstance() {
            if (Manager.#instance === null) {
                throw 'Project grid manager is uninitialized';
            }

            return Manager.#instance;
        }

        static initialize({gridId, componentName, deleteProjectAction}) {
            Manager.#instance = new Manager({
                gridId: gridId,
                componentName: componentName,
                deleteProjectAction: deleteProjectAction
            });
        }

        /**
         * @param {int} projectId
         */
        deleteProject(projectId) {
            const confirm = new BX.UI.Dialogs.MessageBox({
                title: BX.message('INVESTMENT_PROJECT_LIST_DELETE_CONFIRM_TITLE'),
                message: BX.message('INVESTMENT_PROJECT_LIST_DELETE_CONFIRM_MESSAGE'),
                buttons: BX.UI.Dialogs.MessageBoxButtons.OK_CANCEL,
                onOk: () => {
                    confirm.close();

                    BX.ajax.runComponentAction(
                        this.componentName,
                        this.deleteProjectAction,
                        {
                            mode: 'class',
                            data: {
                                projectId: projectId
                            }
                        }
                    ).then((response) => {
                        this.grid.reload();
                        BX.UI.Notification.Center.notify({
                            content: BX.message('INVESTMENT_PROJECT_LIST_DELETE_NOTIFY_MESSAGE')
                                .replace('#PROJECT_TITLE#', response.data.title)
                        })
                    }).catch((response) => {
                        const error = response.errors[0];
                        BX.UI.Dialogs.MessageBox.alert(
                            error.message,
                            BX.message('INVESTMENT_PROJECT_LIST_DELETE_ERROR_TITLE')
                        );
                    });
                }
            });

            confirm.show();
        }
    }
})();