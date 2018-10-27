// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import transition from "transition-to-from-auto";

const MENU_LIST_INIT = () => {
    const MENU_ITEMS   = document.querySelectorAll(".menu-list__item");
    const MENU_LINKS   = document.querySelectorAll(".menu-list__link");
    const MENU_TOGGLES = document.querySelectorAll(".menu-list__toggle");

    // function to mark elements as inactive
    // @param  {Element}  elem - An element to mark as inactive
    const MARK_MENU_ITEM_INACTIVE = (elem) => {
        const CHILD_MENU_LISTS = elem.querySelectorAll(".menu-list.--accordion");

        elem.classList.remove("is-active");

        // mark all child menu lists as hidden
        if (CHILD_MENU_LISTS) {
            CHILD_MENU_LISTS.forEach((elem) => {
                const CHILD_MENU_ITEMS = elem.querySelectorAll(".menu-list__item");

                // mark the list as hidden
                elem.setAttribute("aria-hidden", "true");
                transition({element: elem, val: "0"});

                // mark all child menu items as hidden
                if (CHILD_MENU_ITEMS) {
                    CHILD_MENU_ITEMS.forEach((elem) => {
                        elem.classList.remove("is-active");
                    });
                }
            });
        }
    };

    // function to mark parent elements as inactive
    // @param  {Element}  elem - An element to mark parents inactive
    const MARK_MENU_ITEM_PARENTS_INACTIVE = (elem) => {
        let parent = elem.parentNode;

        setTimeout(() => {
            while (parent && parent.nodeType === 1 && !parent.classList.contains("menu-list__container")) {
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
        const CHILDREN   = elem.childNodes;
        const CHILD_MENU = elem.querySelector("#" + elem.id + " > .menu-list.--accordion");

        elem.classList.add("is-active");
        transition({element: CHILD_MENU, val: "auto"});

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
                MARK_MENU_ITEM_SIBLINGS_INACTIVE(MENU_ITEMS[i]);
                MARK_MENU_ITEM_ACTIVE(MENU_ITEMS[i]);
            }, { passive: true });

            // close on mouseout
            MENU_ITEMS[i].addEventListener("mouseout", () => {
                MARK_MENU_ITEM_INACTIVE(MENU_ITEMS[i]);
            }, { passive: true });
        }

        // check if the menu is touchable
        if (MENU_ITEMS[i].parentElement.dataset.touch === "true") {
            // mark active on touch
            MENU_ITEMS[i].addEventListener("touchstart", (e) => {
                // check if the element is already active
                if (MENU_ITEMS[i].classList.contains("--parent") && !MENU_ITEMS[i].classList.contains("is-active")) {
                    e.preventDefault();
                    MARK_MENU_ITEM_SIBLINGS_INACTIVE(MENU_ITEMS[i]);
                    MARK_MENU_ITEM_ACTIVE(MENU_ITEMS[i]);
                }
            });
        }
    }

    // handle interactions with menu-list_link elements
    for (let i = 0; i < MENU_LINKS.length; i++) {
        // mark inactive on blur (only if no other siblings or children are focused)
        MENU_LINKS[i].addEventListener("blur", () => {
            MARK_MENU_ITEM_PARENTS_INACTIVE(MENU_LINKS[i]);
        }, { passive: true });
    }

    // handle interactions with menu-list_toggle elements
    for (let i = 0; i < MENU_TOGGLES.length; i++) {
        // mark active on click
        MENU_TOGGLES[i].addEventListener("click", (e) => {
            e.preventDefault();

            if (MENU_TOGGLES[i].parentNode.classList.contains("is-active")) {
                MARK_MENU_ITEM_INACTIVE(MENU_TOGGLES[i].parentNode);
            } else {
                MARK_MENU_ITEM_ACTIVE(MENU_TOGGLES[i].parentNode);
            }
        });

        // mark inactive on blur (only if no other siblings or children are focused)
        MENU_TOGGLES[i].addEventListener("blur", () => {
            MARK_MENU_ITEM_PARENTS_INACTIVE(MENU_TOGGLES[i]);
        }, { passive: true });
    }
};

// init the function
MENU_LIST_INIT();
