import React, { useContext, useState } from "react";
import { CreateNftContext } from "./state";
import { SLUG, FRONTENDMEDIAURL } from "../../../../../common/store";
const { __ } = wp.i18n;

const Stats = () => {
  const { state, dispatch } = useContext(CreateNftContext);
  const [traitOpen, setTraitOpen] = useState(false);
  const [type, setType] = useState("");
  const [value, setValue] = useState("");
  const [maxValue, setMaxValue] = useState("");

  const onTypeChange = (e) => setType(e.target.value.trim());
  const onValueChange = (e) => setValue(e.target.value.trim());
  const onMaxValueChange = (e) => setMaxValue(e.target.value.trim());
  const hideShowTraitsInput = () => setTraitOpen(!traitOpen);

  const setStats = () => {
    if (!value || !maxValue || !type) return null;
    if (parseInt(value) > parseInt(maxValue)) {
      throw new Error("Value cant be greater then max value.");
    }

    const temp = [
      ...state.stats,
      {
        trait_type: type,
        value: value,
        max_value: maxValue,
        display_type: "number",
      },
    ];

    dispatch({ type: "SET_STATS", payload: temp });
    setType("");
    setValue("");
    setMaxValue("");
  };

  return (
    <div className="stats mb-small mt-small">
      <div className="meta__header">
        <div>
          <h2 className="header-two">{__("Stats", SLUG)}</h2>
          <p className="pra-one">
            {__("Numerical traits that just show as numbers", SLUG)}
          </p>
        </div>
        <button onClick={hideShowTraitsInput}>+</button>
      </div>
      {traitOpen ? (
        <div className="meta__input  mt-small mb-small">
          <div className="meta__input__fields labels">
            <label htmlFor="trait_type">
              <input
                type="text"
                placeholder={__("Type", SLUG)}
                id="trait_type"
                onChange={onTypeChange}
                value={type}
              />
            </label>
            <label htmlFor="trait_value">
              <input
                type="number"
                placeholder={__("Value", SLUG)}
                id="trait_value"
                onChange={onValueChange}
                value={value}
              />
            </label>
            <label htmlFor="trait_max_value">
              <input
                type="number"
                placeholder={__("Max value", SLUG)}
                id="trait_max_value"
                onChange={onMaxValueChange}
                value={maxValue}
              />
            </label>
            <button onClick={setStats}>{__("Add", SLUG)}</button>
            <img
              src={`${FRONTENDMEDIAURL}cross.svg`}
              onClick={hideShowTraitsInput}
            />
          </div>
        </div>
      ) : null}
      <TraitStats />
    </div>
  );
};

const TraitStats = () => {
  const { state, dispatch } = useContext(CreateNftContext);
  if (!state.stats.length) return null;

  const removeTraitStat = (index) => {
    const temp = state.stats.filter((_item, i) => i != index);
    dispatch({ type: "SET_STATS", payload: temp });
  };

  return (
    <div className="trait-stats">
      {state.stats.map((cur, i) => (
        <div key={i} className="stat">
          <div className="stat__flex">
            <p className="pra-one">{cur.trait_type}</p>
            <p className="pra-one">
              {cur.value} {__("of", SLUG)} {cur.max_value}
            </p>
          </div>
          <img
            onClick={() => {
              removeTraitStat(i);
            }}
            src={`${FRONTENDMEDIAURL}cross.svg`}
          />
        </div>
      ))}
    </div>
  );
};

export default Stats;
