// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

const MENU_TO_EDIT = document.querySelector("#menu-to-edit");

/**
 * Verify that we're trying to edit a menu
 */
if (MENU_TO_EDIT) {
    /**
     * Retrieve all possible menu locations
     */
    const MENU_LOCATIONS = document.querySelectorAll(".menu-theme-locations [id^='locations-'");

    /**
     * Compare an array of locations for a field with locations assigned to the current menu
     *
     * @param {Array} field_locations
     */
    const COMPARE_LOCATIONS = (field_locations) => {
        const SELECTED_LOCATIONS = [];

        /**
         * Find which menu locations are currently selected
         */
        MENU_LOCATIONS.forEach((location) => {
            if (location.checked) {
                SELECTED_LOCATIONS.push(location.name.match(/menu-locations\[(.*?)\]/)[1]);
            }
        });

        /**
         * Determine if any locations are shared between the field and the current menu
         */
        return field_locations.some((value) => {
            return SELECTED_LOCATIONS.indexOf(value) > -1 ? true : false;
        });
    };

    /**
     * Insert custom fields when a new menu item is added
     *
     * @param {Array} MUTATIONS_LIST
     */
    const INSERT_FIELDS = (MUTATIONS_LIST) => {
        /**
         * Loop through each observed mutation
         */
        MUTATIONS_LIST.forEach((mutation) => {
            /**
             * Check if at least one node was added, and if so, loop through added nodes
             */
            if (mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach((node) => {
                    /**
                     * Make sure the node is a new menu item with the class "pending" and has not already had custom fields added
                     */
                    if (node.classList.contains("menu-item", "pending") && !node.querySelector(".custom-fields-container")) {
                        /**
                         * Extract the menu item ID from the id attribute
                         */
                        const ITEM_ID = parseInt(node.id.replace("menu-item-", ""));

                        /**
                         * Find the target element to insert each custom field before
                         */
                        const FIELDSET = node.querySelector("fieldset");

                        /**
                         * Create a new div to insert elements in to
                         */
                        const CONTAINER = document.createElement("div");

                        /**
                         * Add a special class so we can more easily identify it later
                         */
                        CONTAINER.classList.add("custom-fields-container");

                        /**
                         * Loop through each field and add it to the container
                         */
                        l10n.custom_fields.forEach((field) => {
                            /**
                             * Replace placeholder values
                             */
                            field = field.replace(/{{ item_id }}/g, ITEM_ID);
                            field = field.replace(/{{ item_value }}/g, "");
                            field = field.replace(/{{ item_checked }}/g, "");

                            /**
                             * Determine what menu location(s) the field is assigned to
                             */
                            const FIELD_LOCATIONS = JSON.parse(field.match(/data-locations='(.+?)'/)[1]);

                            /**
                             * Remove ` hidden-field` if at least one location is shared
                             */
                            if (COMPARE_LOCATIONS(FIELD_LOCATIONS)) {
                                field = field.replace(/ hidden-field/, "");
                            }

                            /**
                             * Append the field to the container
                             */
                            CONTAINER.innerHTML = CONTAINER.innerHTML + field;
                        });

                        FIELDSET.parentNode.insertBefore(CONTAINER, FIELDSET);
                    }
                });
            }
        });
    };

    /**
     * Watch for mutations on the menu in order to insert custom fields
     */
    const INSERT_OBSERVER = new MutationObserver(INSERT_FIELDS);

    INSERT_OBSERVER.observe(MENU_TO_EDIT, { childList: true });

    /**
     * Watch for changes to menu location in order to update custom field visibility
     *
     * @TODO Insert Screen Options fields
     */
    MENU_LOCATIONS.forEach((location) => {
        location.addEventListener("change", () => {
            /**
             * Find all current custom fields
             */
            const CUSTOM_FIELDS = document.querySelectorAll(".custom-fields-container p[data-locations]");

            /**
             * Compare each custom fields lcoations to the selected locations and show or hide the
             * field depending on if matches are found
             */
            CUSTOM_FIELDS.forEach((field) => {
                const FIELD_NAME  = field.getAttribute("class").match(/field-(.*?) /)[1];
                const HIDE_OPTION = document.getElementById(`${FIELD_NAME}-hide`);

                if ((!HIDE_OPTION || HIDE_OPTION.checked === true) && COMPARE_LOCATIONS(JSON.parse(field.dataset.locations))) {
                    field.classList.remove("hidden-field");
                } else {
                    field.classList.add("hidden-field");
                }
            });
        });
    });
}
