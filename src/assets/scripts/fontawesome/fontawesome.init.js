// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import { library, dom } from "@fortawesome/fontawesome-svg-core";

import { faBars           as fasFaBars           } from "@fortawesome/pro-solid-svg-icons/faBars";
import { faCalendar       as fasFaCalendar       } from "@fortawesome/pro-solid-svg-icons/faCalendar";
import { faCaretDown      as fasFaCaretDown      } from "@fortawesome/pro-solid-svg-icons/faCaretDown";
import { faCaretLeft      as fasFaCaretLeft      } from "@fortawesome/pro-solid-svg-icons/faCaretLeft";
import { faCaretRight     as fasFaCaretRight     } from "@fortawesome/pro-solid-svg-icons/faCaretRight";
import { faCircle         as fasFaCircle         } from "@fortawesome/pro-solid-svg-icons/faCircle";
import { faClock          as fasFaClock          } from "@fortawesome/pro-solid-svg-icons/faClock";
import { faComment        as fasFaComment        } from "@fortawesome/pro-solid-svg-icons/faComment";
import { faEnvelope       as fasFaEnvelope       } from "@fortawesome/pro-solid-svg-icons/faEnvelope";
import { faFolder         as fasFaFolder         } from "@fortawesome/pro-solid-svg-icons/faFolder";
import { faQuestionCircle as fasFaQuestionCircle } from "@fortawesome/pro-solid-svg-icons/faQuestionCircle";
import { faSearch         as fasFaSearch         } from "@fortawesome/pro-solid-svg-icons/faSearch";
import { faTag            as fasFaTag            } from "@fortawesome/pro-solid-svg-icons/faTag";
import { faMapMarkerAlt   as fasFaMapMarkerAlt   } from "@fortawesome/pro-solid-svg-icons/faMapMarkerAlt";
import { faMoneyBill      as fasFaMoneyBill      } from "@fortawesome/pro-solid-svg-icons/faMoneyBill";
import { faMousePointer   as fasFaMousePointer   } from "@fortawesome/pro-solid-svg-icons/faMousePointer";
import { faPhone          as fasFaPhone          } from "@fortawesome/pro-solid-svg-icons/faPhone";
import { faUserCircle     as fasFaUserCircle     } from "@fortawesome/pro-solid-svg-icons/faUserCircle";

/**
 * Add solid icons
 */
library.add(fasFaBars, fasFaCalendar, fasFaCaretDown, fasFaCaretLeft, fasFaCaretRight, fasFaCircle, fasFaClock, fasFaComment, fasFaEnvelope, fasFaFolder, fasFaQuestionCircle, fasFaSearch, fasFaTag, fasFaMapMarkerAlt, fasFaMoneyBill, fasFaMousePointer, fasFaPhone, fasFaUserCircle);

/**
 * Watch the DOM to insert icons
 */
dom.watch();
