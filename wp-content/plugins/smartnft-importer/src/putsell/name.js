import React, { useContext } from "react";
import { FORM_CONTEX } from "./state";
const { __ } = wp.i18n;
import { Form, Input } from "antd";
const SLUG = "smartnft_importer";

const NameField = () => {
  const { dispatch } = useContext(FORM_CONTEX);

  const onNameChange = (e) => {
    const value = e.target.value;
    if (!value) {
      return dispatch({ type: "CHANGE_NAME", payload: "" });
    }
    dispatch({ type: "CHANGE_NAME", payload: value });
  };

  return (
    <Form layout="vertical">
      <Form.Item label="name" tooltip="This is a optional field">
        <Input
          onChange={onNameChange}
          placeholder={__("Optional, otherwise will use current name.", SLUG)}
        />
      </Form.Item>
    </Form>
  );
};

export default NameField;
