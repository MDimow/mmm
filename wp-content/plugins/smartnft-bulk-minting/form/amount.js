import React, { useContext } from "react";
import { CreateNftContext } from "./state";
import { SLUG } from "../../../../../common/store";
const { __ } = wp.i18n;

const Amount = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  const onChange = (e) => {
    const value = parseInt(e.target.value);
    dispatch({ type: "CHANGE_AMOUNT", payload: value });
  };

  if (state.standard !== "Erc1155") return null;

  return (
    <div>
      <label htmlFor="amount">
        <p>{__("Amount", SLUG)}</p>
        <input
          type="number"
          id="amount"
          placeholder={__("amount", SLUG)}
          value={state.amount}
          onChange={onChange}
        />
      </label>
    </div>
  );
};

export default Amount;
