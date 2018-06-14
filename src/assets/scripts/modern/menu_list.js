// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

const MENU_LIST_INIT = () => {
    const MENU_ITEMS   = document.querySelectorAll(".menu-list_item");
    const MENU_LINKS   = document.querySelectorAll(".menu-list_link");
    const MENU_TOGGLES = document.querySelectorAll(".menu-list_toggle");

    // function to mark elements as inactive
    // @param  {Element}  elem - An element to mark as inactive
    const MARK_MENU_ITEM_INACTIVE = (elem) => {
        const CHILDREN = elem.getElementsByTagName("*");

        elem.classList.remove("is-active");

        for (let i = 0; i < CHILDREN.length; i++) {
            if (CHILDREN[i].nodeType === 1) {
                CHILDREN[i].classList.remove("is-active");

                if (CHILDREN[i].hasAttribute("aria-hidden")) {
                    CHILDREN[i].setAttribute("aria-hidden", "true");
                }
            }
        }
    };

    // function to mark parent elements as inactive
    // @param  {Element}  elem - An element to mark parents inactive
    const MARK_MENU_ITEM_PARENTS_INACTIVE = (elem) => {
        let parent = elem.parentNode;

        setTimeout(() => {
            while (parent && parent.nodeType === 1 && !parent.classList.contains("menu-list_container")) {
                if (parent.classList.contains("is-active") && !parent.contains(document.activeElement)) {
                    MARK_MENU_ITEM_INACTIVE(parent);
                }

                parent = parent.parentNode;
            }
        }, 10);
    };

    // function to mark sibling elements as inactive
    // @param  {Element}  elem - An element to mark siblings inactive
    const MARK_MENU_ITEM_SIBLINGS_INACTIVE = (elem) => {
        const SIBLINGS = elem.parentNode.childNodes;

        // mark all siblings as inactive
        for (let i = 0; i < SIBLINGS.length; i++) {
            if (SIBLINGS[i].nodeType === 1 && SIBLINGS[i] !== elem) {
                MARK_MENU_ITEM_INACTIVE(SIBLINGS[i]);
            }
        }
    };

    // function to mark elements as active
    // @param  {Element}  elem - An element to mark as active
    const MARK_MENU_ITEM_ACTIVE = (elem) => {
        const CHILDREN = elem.childNodes;

        elem.classList.add("is-active");

        for (let i = 0; i < CHILDREN.length; i++) {
            if (CHILDREN[i].nodeType === 1 && CHILDREN[i].hasAttribute("aria-hidden")) {
                CHILDREN[i].setAttribute("aria-hidden", "false");
            }
        }
    };

    // handle touch away from menu-list elements
    document.addEventListener("touchstart", (e) => {
        let parent_element = e.target.parentElement;
        let clicked_on_menu = false;

        // loop through all parent elements until it is determiend if a menu was in the stack
        while (parent_element && clicked_on_menu === false) {
            if (parent_element.classList.contains("menu-list") || parent_element.dataset.menu === "true" || e.target.dataset.menu === "true") {
                clicked_on_menu = true;
            }

            parent_element = parent_element.parentElement;
        }

        // close all menus if a menu wasn't clicked
        // @TODO make sure the touched menu-list is active
        if (clicked_on_menu === false) {
            for (let i = 0; i < MENU_ITEMS.length; i++) {
                MARK_MENU_ITEM_INACTIVE(MENU_ITEMS[i]);
            }
        }
    });

    // handle interactions with menu-list_item elements
    for (let i = 0; i < MENU_ITEMS.length; i++) {
        // check if the menu is hoverable
        if (MENU_ITEMS[i].parentElement.dataset.hover === "true") {
            // open on mouseover
            MENU_ITEMS[i].addEventListener("mouseover", () => {
                MARK_MENU_ITEM_SIBLINGS_INACTIVE(this);
                MARK_MENU_ITEM_ACTIVE(this);
            });

            // close on mouseout
            MENU_ITEMS[i].addEventListener("mouseout", () => {
                MARK_MENU_ITEM_INACTIVE(this);
            });
        }

        // check if the menu is touchable
        if (MENU_ITEMS[i].parentElement.dataset.touch === "true") {
            // mark active on touch
            MENU_ITEMS[i].addEventListener("touchstart", (e) => {
                // check if the element is already active
                if (this.classList.contains("-parent") && !this.classList.contains("is-active")) {
                    e.preventDefault();
                    MARK_MENU_ITEM_SIBLINGS_INACTIVE(this);
                    MARK_MENU_ITEM_ACTIVE(this);
                }
            });
        }
    }

    // handle interactions with menu-list_link elements
    for (let i = 0; i < MENU_LINKS.length; i++) {
        // mark inactive on blur (only if no other siblings or children are focused)
        MENU_LINKS[i].addEventListener("blur", () => {
            MARK_MENU_ITEM_PARENTS_INACTIVE(this);
        });
    }

    // handle interactions with menu-list_toggle elements
    for (let i = 0; i < MENU_TOGGLES.length; i++) {
        // mark active on click
        MENU_TOGGLES[i].addEventListener("click", (e) => {
            e.preventDefault();

            if (this.parentNode.classList.contains("is-active")) {
                MARK_MENU_ITEM_INACTIVE(this.parentNode);
            } else {
                MARK_MENU_ITEM_ACTIVE(this.parentNode);
            }
        });

        // mark inactive on blur (only if no other siblings or children are focused)
        MENU_TOGGLES[i].addEventListener("blur", () => {
            MARK_MENU_ITEM_PARENTS_INACTIVE(this);
        });
    }
};

// init the function
MENU_LIST_INIT();
