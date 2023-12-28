import React, { useContext } from "react";
import { CreateNftContext } from "./state";
import { SLUG } from "../../../../../common/store";
const { __ } = wp.i18n;

const Description = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  const onChange = (e) => {
    const value = e.target.value;
    dispatch({ type: "CHANGE_META", payload: value, key: "DESCRIPTION" });
  };

  return (
    <div>
      <label htmlFor="description" className="form-wallet__label">
        <p className="form-wallet__label-text header-two">
          {__("Description", SLUG)}
        </p>
        <textarea
          onChange={onChange}
          value={state.meta.description}
          id="description"
          cols="30"
          rows="5"
          placeholder={__("Provide a detailed description of your item.", SLUG)}
        ></textarea>
      </label>
    </div>
  );
};

export default Description;
