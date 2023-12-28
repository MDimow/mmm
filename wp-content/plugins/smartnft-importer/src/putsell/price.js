import React, { useContext } from "react";
import { FORM_CONTEX } from "./state";
const currencySymbol = importer_local.ACTIVE_CONTRACT?.network?.currencySymbol;
const { __ } = wp.i18n;
import { Form, Input} from 'antd';

const SLUG = "smartnft_importer";
const PriceField = () => {
  const { dispatch } = useContext(FORM_CONTEX);

  const onPriceChange = (e) => {
    const value = e.target.value;
    if (!value) {
      return dispatch({ type: "CHANGE_PRICE", payload: 0 });
    }
    dispatch({ type: "CHANGE_PRICE", payload: value });
  };

  return (
    <Form layout="vertical">
      <Form.Item label="Price" required tooltip="This is a required field">
        <Input addonAfter={currencySymbol} onChange={onPriceChange} placeholder={__("Enter price for one item", SLUG)} />
      </Form.Item>
    </Form>
  );
};

export default PriceField;
