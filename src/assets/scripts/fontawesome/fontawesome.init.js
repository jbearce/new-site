// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import { library, dom } from "@fortawesome/fontawesome-svg-core";

import { faAngleDown      as farFaAngleDown      } from "@fortawesome/pro-regular-svg-icons/faAngleDown";
import { faBars           as farFaBars           } from "@fortawesome/pro-regular-svg-icons/faBars";
import { faCalendar       as farFaCalendar       } from "@fortawesome/pro-regular-svg-icons/faCalendar";
import { faClock          as farFaClock          } from "@fortawesome/pro-regular-svg-icons/faClock";
import { faComment        as farFaComment        } from "@fortawesome/pro-regular-svg-icons/faComment";
import { faEnvelope       as farFaEnvelope       } from "@fortawesome/pro-regular-svg-icons/faEnvelope";
import { faFolder         as farFaFolder         } from "@fortawesome/pro-regular-svg-icons/faFolder";
import { faQuestionCircle as farFaQuestionCircle } from "@fortawesome/pro-regular-svg-icons/faQuestionCircle";
import { faSearch         as farFaSearch         } from "@fortawesome/pro-regular-svg-icons/faSearch";
import { faTag            as farFaTag            } from "@fortawesome/pro-regular-svg-icons/faTag";
import { faMapMarkerAlt   as farFaMapMarkerAlt   } from "@fortawesome/pro-regular-svg-icons/faMapMarkerAlt";
import { faMoneyBill      as farFaMoneyBill      } from "@fortawesome/pro-regular-svg-icons/faMoneyBill";
import { faMousePointer   as farFaMousePointer   } from "@fortawesome/pro-regular-svg-icons/faMousePointer";
import { faPhone          as farFaPhone          } from "@fortawesome/pro-regular-svg-icons/faPhone";
import { faUserCircle     as farFaUserCircle     } from "@fortawesome/pro-regular-svg-icons/faUserCircle";

import { faCaretDown      as fasFaCaretDown      } from "@fortawesome/pro-solid-svg-icons/faCaretDown";
import { faCaretLeft      as fasFaCaretLeft      } from "@fortawesome/pro-solid-svg-icons/faCaretLeft";
import { faCaretRight     as fasFaCaretRight     } from "@fortawesome/pro-solid-svg-icons/faCaretRight";
import { faCircle         as fasFaCircle         } from "@fortawesome/pro-solid-svg-icons/faCircle";

/**
 * Add regular icons
 */
library.add(farFaAngleDown, farFaBars, farFaCalendar, farFaClock, farFaComment, farFaEnvelope, farFaFolder, farFaQuestionCircle, farFaSearch, farFaTag, farFaMapMarkerAlt, farFaMoneyBill, farFaMousePointer, farFaPhone, farFaUserCircle);

/**
 * Add solid icons
 */
library.add(fasFaCaretDown, fasFaCaretLeft, fasFaCaretRight, fasFaCircle);

/**
 * Watch the DOM to insert icons
 */
dom.watch();
