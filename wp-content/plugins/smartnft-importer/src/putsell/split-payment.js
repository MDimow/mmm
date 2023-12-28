import React, { useContext } from "react";
import { FORM_CONTEX } from "./state";
import { Form, Input, Switch, Space, Button } from "antd";
const SNFT_IMPORTER_MEDIA_URL = importer_local.SNFT_IMPORTER_MEDIA_URL;
const { __ } = wp.i18n;
const SLUG = "smartnft_importer";

const SplitPaymentComponent = () => {
  const { state, dispatch, web3Provider } = useContext(FORM_CONTEX);

  const addSplitPayments = () => {
    const newSplitPayments = [
      ...state.splitPaymentsAccounts,
      { address: "", percentage: 0 },
    ];

    dispatch({ type: "CHANGE_SPLIT_PAYMENT", payload: newSplitPayments });
  };

  const changeSplitPayments = (index, property, value) => {
    if (property === "percentage") {
      const percentage = parseFloat(value);

      if (isNaN(percentage)) {
        errorMessage(__("Give a valid percentage.", SLUG));
        return;
      }

      const percentageWithOnlyTwoFlotPoinNumber = percentage.toFixed(2);

      if (percentageWithOnlyTwoFlotPoinNumber > 100) {
        errorMessage(__("Total payments must be 100%", SLUG));
        throw new Error(__("Total payments must be 100%", SLUG));
      }

      const newSplitPayments = [...state.splitPaymentsAccounts];
      newSplitPayments[index].percentage = percentageWithOnlyTwoFlotPoinNumber;
      console.log(newSplitPayments);

      dispatch({ type: "CHANGE_SPLIT_PAYMENT", payload: newSplitPayments });
    }

    if (property === "address") {
      const newSplitPayments = [...state.splitPaymentsAccounts];
      newSplitPayments[index].address = value;
      dispatch({ type: "CHANGE_SPLIT_PAYMENT", payload: newSplitPayments });
    }
  };

  const removeSplitPayments = (index) => {
    if (state.splitPaymentsAccounts.length < index) {
      errorMessage(__("Cant remove. Try again or reload the page.", SLUG));
      throw new Error(__("Cant remove. Try again or reload the page.", SLUG));
    }

    const newSplitPayments = [...state.splitPaymentsAccounts];

    newSplitPayments.splice(index, 1);

    if (!newSplitPayments.length) {
      return dispatch({ type: "RESET_SPLIT_PAYMENT" });
    }

    dispatch({ type: "CHANGE_SPLIT_PAYMENT", payload: newSplitPayments });
  };

  const calculatePercentage = () =>
    state.splitPaymentsAccounts.reduce(
      (perValue, curEl) => perValue + parseFloat(curEl.percentage),
      0
    );

  const changeSplitPaymentActivation = (hasSplitPayment = false) => {
    if (hasSplitPayment === false) {
      dispatch({ type: "RESET_SPLIT_PAYMENT" });
    }

    if (hasSplitPayment === true) {
      dispatch({ type: "ACTIVE_SPLIT_PAYMENT" });
    }
  };

  return (
    <>
      <div className="form-img-upload__collection">
        {/* <p className="header-two">{__("Split Payments", SLUG)}</p>
        <label className="switch">
          <input
            type="checkbox"
            checked={state.hasSplitPayment}
            onChange={(e) => {
              changeSplitPaymentActivation(e.target.checked);
            }}
          />
          <span className="slider round"></span>
        </label> */}
        <Form.Item label={__("Split Payments", SLUG)} valuePropName="checked">
          <Switch
            checked={state.hasSplitPayment}
            onChange={(e) => {
              changeSplitPaymentActivation(!state.hasSplitPayment);
            }}
          />
        </Form.Item>
        <p className="form-img-upload__newline-pra">
          {__(
            "Add multiple address to receive your payments.Only Creator will be able to see it. Must total 100%",
            SLUG
          )}
        </p>
      </div>

      {state.hasSplitPayment && (
        <>
          {state.splitPaymentsAccounts.map((cur, index) => (
            <Space
              style={{ display: "flex", marginBottom: 8, width: "100%" }}
              align="baseline"
              key={index}
            >
              <Form.Item style={{ marginBottom: 10 }}>
                <Input
                  placeholder={__("Wallet Address", SLUG)}
                  onChange={(e) => {
                    changeSplitPayments(index, "address", e.target.value);
                  }}
                />
              </Form.Item>
              <Form.Item style={{ marginBottom: 10 }}>
                <Input
                  placeholder={__("Amount in %", SLUG)}
                  onChange={(e) => {
                    if (!e.target.value) {
                      return changeSplitPayments(index, "percentage", 0);
                    }
                    changeSplitPayments(index, "percentage", e.target.value);
                  }}
                />
              </Form.Item>
              <img
                src={`${SNFT_IMPORTER_MEDIA_URL}delete.svg`}
                style={{ width: 16, verticalAlign: "top" }}
                onClick={(e) => {
                  removeSplitPayments(index);
                }}
              />
            </Space>
          ))}
        </>
      )}

      {state.hasSplitPayment && (
        <>
          {calculatePercentage() > 100 && (
            <p className="form-img-upload__error-split-payment pra-one">
              {__("Total payments must be 100%", SLUG)}
            </p>
          )}
        </>
      )}

      {state.hasSplitPayment && (
        // <button
        //   onClick={(e) => {
        //     e.preventDefault();
        //     addSplitPayments();
        //   }}
        //   className="pra-one form-img-upload__add-new-split-payment"
        //   disabled={calculatePercentage() >= 100 ? true : false}
        // >
        //   {__("+ add more", SLUG)}
        // </button>
        <Button
          type="dashed"
          onClick={(e) => {
            e.preventDefault();
            addSplitPayments();
          }}
        >
          {__("+ Add more", SLUG)}
        </Button>
      )}
    </>
  );
};
export default SplitPaymentComponent;
