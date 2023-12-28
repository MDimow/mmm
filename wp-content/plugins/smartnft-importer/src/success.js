import React from "react";

import { escapeHTML } from "@wordpress/escape-html";
const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

const Success = () => {
  window.setTimeout(() => {
    window.location.reload();
  }, 2000);

  return (
    <h3 style={{ textAlign: "center" }}>
      {escapeHTML(__("hurray! imported successfully!!", SLUG))}
    </h3>
  );
};

export default Success;
