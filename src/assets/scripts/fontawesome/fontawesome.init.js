// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import { library, dom } from "@fortawesome/fontawesome-svg-core";

import { faAngleDown      as farFaAngleDown      } from "@fortawesome/pro-regular-svg-icons/faAngleDown";
import { faCalendar       as farFaCalendar       } from "@fortawesome/pro-regular-svg-icons/faCalendar";
import { faClock          as farFaClock          } from "@fortawesome/pro-regular-svg-icons/faClock";
import { faQuestionCircle as farFaQuestionCircle } from "@fortawesome/pro-regular-svg-icons/faQuestionCircle";
import { faSearch         as farFaSearch         } from "@fortawesome/pro-regular-svg-icons/faSearch";
import { faMoneyBill      as farFaMoneyBill      } from "@fortawesome/pro-regular-svg-icons/faMoneyBill";
import { faUserCircle     as farFaUserCircle     } from "@fortawesome/pro-regular-svg-icons/faUserCircle";

import { faBars         as fasFaBars         } from "@fortawesome/pro-solid-svg-icons/faBars";
import { faCalendar     as fasFaCalendar     } from "@fortawesome/pro-solid-svg-icons/faCalendar";
import { faCaretDown    as fasFaCaretDown    } from "@fortawesome/pro-solid-svg-icons/faCaretDown";
import { faCaretLeft    as fasFaCaretLeft    } from "@fortawesome/pro-solid-svg-icons/faCaretLeft";
import { faCaretRight   as fasFaCaretRight   } from "@fortawesome/pro-solid-svg-icons/faCaretRight";
import { faCircle       as fasFaCircle       } from "@fortawesome/pro-solid-svg-icons/faCircle";
import { faEnvelope     as fasFaEnvelope     } from "@fortawesome/pro-solid-svg-icons/faEnvelope";
import { faFolder       as fasFaFolder       } from "@fortawesome/pro-solid-svg-icons/faFolder";
import { faMapMarkerAlt as fasFaMapMarkerAlt } from "@fortawesome/pro-solid-svg-icons/faMapMarkerAlt";
import { faMoneyBill    as fasFaMoneyBill    } from "@fortawesome/pro-solid-svg-icons/faMoneyBill";
import { faPhone        as fasFaPhone        } from "@fortawesome/pro-solid-svg-icons/faPhone";
import { faSearch       as fasFaSearch       } from "@fortawesome/pro-solid-svg-icons/faSearch";
import { faTag          as fasFaTag          } from "@fortawesome/pro-solid-svg-icons/faTag";
import { faUserCircle   as fasFaUserCircle   } from "@fortawesome/pro-solid-svg-icons/faUserCircle";

/**
 * Add regular icons
 */
library.add(farFaAngleDown, farFaCalendar, farFaClock, farFaQuestionCircle, farFaSearch, farFaMoneyBill, farFaUserCircle);

/**
 * Add solid icons
 */
library.add(fasFaBars, fasFaCalendar, fasFaCaretDown, fasFaCaretLeft, fasFaCaretRight, fasFaCircle, fasFaEnvelope, fasFaFolder, fasFaMapMarkerAlt, fasFaMoneyBill, fasFaPhone, fasFaSearch, fasFaTag, fasFaBars, fasFaUserCircle);

/**
 * Watch the DOM to insert icons
 */
dom.watch();
