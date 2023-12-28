import React, { useContext } from "react";
import { CreateNftContext } from "./state";
import { SLUG } from "../../../../../common/store";
const { __ } = wp.i18n;

const Name = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  const onChange = (e) => {
    const value = e.target.value;
    dispatch({ type: "CHANGE_META", key: "NAME", payload: value });
  };

  return (
    <div>
      <label htmlFor="name" className="form-wallet__label">
        <p className="form-wallet__label-text header-two">{__("Name", SLUG)}</p>
        <input
          type="text"
          id="name"
          placeholder={__("e. g. Redeemable T-Shirt with logo", SLUG)}
          value={state.meta.name}
          onChange={onChange}
        />
      </label>
    </div>
  );
};

export default Name;
