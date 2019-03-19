/* eslint-env browser */
/* globals bt_DIST_PATH */

/** Dynamically set absolute public path from current protocol and host */
if (bt_DIST_PATH) {
  __webpack_public_path__ = bt_DIST_PATH; // eslint-disable-line no-undef, camelcase
}
