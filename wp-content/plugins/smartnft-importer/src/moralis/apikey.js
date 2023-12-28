import React, { useEffect, useState } from "react";
import { escapeHTML } from "@wordpress/escape-html";
import { notification } from "antd";
import { WarningOutlined, CheckCircleOutlined } from "@ant-design/icons";
const MORALIS_API_KEY = importer_local.MORALIS_API_KEY;

const ApiKey = () => {
  const [apikey, setApikey] = useState("");

  const save = async () => {
    if (!apikey) {
      notification.error({
        message: escapeHTML("Api save Error"),
        description: escapeHTML("Please provide api key."),
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });
      throw new Error(escapeHTML(__("Please provide api key.", SLUG)));
    }

    try {
      const res = await jQuery.ajax({
        type: "post",
        url: importer_local.BACKEND_AJAX_URL,
        data: {
          action: "smartnft_importer_save_moralis_api_key",
          api_key: apikey,
        },
      });

      notification.open({
        message: escapeHTML("Key saved"),
        description: escapeHTML("Api key saved successfully."),
        icon: <CheckCircleOutlined style={{ color: "#108ee9" }} />,
      });

      window.setTimeout(() => {
        window.location.reload();
      }, 2000);

      console.log(res);
    } catch (err) {
      console.log(err);
      notification.error({
        message: escapeHTML("Api save Error"),
        description: escapeHTML("Api not saved! Try again."),
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });
      throw new Error(escapeHTML(__("Api not saved! Try again.", SLUG)));
    }
  };

  useEffect(() => {
    setApikey(MORALIS_API_KEY);
  }, []);

  return (
    <div className="apikey">
      <input
        className="input-text"
        type="text"
        value={apikey}
        onChange={(e) => {
          const value = e.target.value;
          setApikey(value);
        }}
        placeholder={escapeHTML("Put Moralis api key")}
      />

      <button className="btn-normal" onClick={save}>
        {escapeHTML("Save")}
      </button>
    </div>
  );
};

export default ApiKey;
