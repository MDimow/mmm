import React, { useContext, useState } from "react";
import { CreateNftContext } from "./state";
import { SLUG, FRONTENDMEDIAURL } from "../../../../../common/store";
const { __ } = wp.i18n;

const Properties = () => {
  const { state, dispatch } = useContext(CreateNftContext);
  const [traitOpen, setTraitOpen] = useState(false);
  const [type, setType] = useState("");
  const [name, setName] = useState("");

  const onTypeChange = (e) => setType(e.target.value.trim());
  const onNameChange = (e) => setName(e.target.value.trim());
  const hideShowTraitsInput = () => setTraitOpen(!traitOpen);

  const setProperties = () => {
    if (!name || !type) return null;

    const temp = [...state.properties, { trait_type: type, value: name }];

    dispatch({ type: "SET_PROPERTIES", payload: temp });
    setType("");
    setName("");
  };

  return (
    <div className="properties mb-small mt-small">
      <div className="meta__header">
        <div>
          <h2 className="header-two">{__("Properties", SLUG)}</h2>
          <p className="pra-one">
            {__("Textual traits that show up as rectangle.", SLUG)}
          </p>
        </div>
        <button onClick={hideShowTraitsInput}>+</button>
      </div>
      {traitOpen ? (
        <div className="meta__input mt-small mb-small">
          <div className="meta__input__fields">
            <label htmlFor="trait_type">
              <input
                type="text"
                placeholder={__("Type", SLUG)}
                id="trait_type"
                onChange={onTypeChange}
                value={type}
              />
            </label>
            <label htmlFor="trait_name">
              <input
                type="text"
                placeholder={__("Name", SLUG)}
                id="trait_name"
                onChange={onNameChange}
                value={name}
              />
            </label>
            <button onClick={setProperties}>{__("Add", SLUG)}</button>
            <img
              src={`${FRONTENDMEDIAURL}cross.svg`}
              onClick={hideShowTraitsInput}
            />
          </div>
        </div>
      ) : null}
      <Traits />
    </div>
  );
};

const Traits = () => {
  const { state, dispatch } = useContext(CreateNftContext);
  if (!state.properties.length) return null;

  const removeTrait = (index) => {
    const temp = state.properties.filter((_item, i) => i != index);
    dispatch({ type: "SET_PROPERTIES", payload: temp });
  };

  return (
    <div className="traits mt-small">
      {state.properties.map((cur, i) => (
        <div className="trait" key={i}>
          <img
            src={`${FRONTENDMEDIAURL}cross.svg`}
            onClick={() => {
              removeTrait(i);
            }}
          />
          <p>{cur.trait_type}</p>
          <h3>{cur.value}</h3>
        </div>
      ))}
    </div>
  );
};

export default Properties;
