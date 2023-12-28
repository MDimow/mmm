import React, { useContext, useEffect, useState } from "react";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import { Button, Row, Col, Space } from "antd";
import { escapeHTML } from "@wordpress/escape-html";

const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

const AddCollectionAndCategory = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  const [collections, setCollections] = useState([]);
  const [categories, setCategories] = useState([]);

  async function fetchCatAndColl() {
    try {
      const res = await jQuery.ajax({
        type: "post",
        url: importer_local.BACKEND_AJAX_URL,
        data: {
          creator: web3Provider.account[0],
          contract_add: importer_local.ACTIVE_CONTRACT.address,
          action: "get_user_collections_and_category",
        },
      });

      console.log(res);

      setCollections(res.data.collections);
      setCategories(res.data.categories);
    } catch (err) {
      console.log(err);
    }
  }

  useEffect(() => {
    fetchCatAndColl();
  }, [web3Provider.account]);

  return (
    <Row>
      <Col span={12}>
        <Space direction="vertical" size={25}>
          <div>
            <p>{escapeHTML(__("Collection", SLUG))}</p>
            <CategoriesAndCollection
              array={collections}
              dispatchType="ADD_COLLECTION"
            />
          </div>
          <div>
            <p>{escapeHTML(__("Create Collection", SLUG))}</p>
            <CreateNewCollectionAndCategory
              createAction="smartnft_create_collection"
              existAction="collection_exist"
              nameKey="collection_name"
              fetchCatAndColl={fetchCatAndColl}
            />
          </div>
        </Space>
      </Col>
      <Col span={12}>
        <Space direction="vertical" size={25}>
          <div>
            <p>{escapeHTML(__("Category", SLUG))}</p>
            <CategoriesAndCollection
              array={categories}
              dispatchType="ADD_CATEGORY"
            />
          </div>
          <div>
            <p>{escapeHTML(__("Create category", SLUG))}</p>
            <CreateNewCollectionAndCategory
              createAction="smartnft_create_category"
              existAction="smartnft_category_exist"
              nameKey="category_name"
              fetchCatAndColl={fetchCatAndColl}
            />
          </div>
        </Space>
      </Col>

      <div style={{ marginTop: 20 }}>
        <PrevStep dispatch={dispatch} />
        <NextStep dispatch={dispatch} />
      </div>
    </Row>
  );
};

const NextStep = ({ dispatch }) => {
  const nextStep = () => {
    dispatch({ type: "CHANGE_STEP", payload: 5 });
  };
  return (
    <Button type="primary" className="btn-normal next-btn" onClick={nextStep}>
      {escapeHTML("Continue")}
    </Button>
  );
};

const PrevStep = ({ dispatch }) => {
  const prevStep = () => {
    dispatch({ type: "CHANGE_SELECTED_NFTS", payload: [] });
    dispatch({ type: "CHANGE_NFTS", payload: [] });
    dispatch({ type: "CHANGE_STEP", payload: 3 });
  };

  return (
    <Button
      style={{ marginRight: 10 }}
      className="btn-normal next-btn"
      onClick={prevStep}
    >
      {escapeHTML(__("Previous", SLUG))}
    </Button>
  );
};

const addCategoryAndCollectionInNfts = (nfts, type, value) => {
  if (type == "ADD_CATEGORY") {
    return nfts.map((cur) => ({
      id: cur.id,
      nft: { ...cur.nft, category: value.trim() },
    }));
  }
  if (type == "ADD_COLLECTION") {
    return nfts.map((cur) => ({
      id: cur.id,
      nft: { ...cur.nft, collection: value.trim() },
    }));
  }
};

const CategoriesAndCollection = ({ array, dispatchType }) => {
  if (!array.length) return <span>Nothing Found</span>;

  const { state, dispatch } = useContext(SNFT_IMPORTER_APP_CONTEX);

  const onChange = (e) => {
    const value = e.target.value ? e.target.value : "";
    dispatch({ type: dispatchType, payload: value });
    const newNfts = addCategoryAndCollectionInNfts(
      state.selectedNfts,
      dispatchType,
      value
    );
    dispatch({ type: "CHANGE_SELECTED_NFTS", payload: newNfts });
  };

  return (
    <>
      <select onChange={onChange}>
        <option value=""></option>
        {array.map((cur, i) => (
          <option key={i} value={cur}>
            {cur}
          </option>
        ))}
      </select>
    </>
  );
};

const CreateNewCollectionAndCategory = ({
  createAction,
  existAction,
  nameKey,
  fetchCatAndColl,
}) => {
  const [exist, setExist] = useState(false);
  const [name, setName] = useState("");
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  const create = async () => {
    try {
      const res = await jQuery.ajax({
        type: "post",
        url: importer_local.BACKEND_AJAX_URL,
        data: {
          accAdd: web3Provider.account[0],
          contract_add: importer_local.ACTIVE_CONTRACT.address,
          name,
          action: createAction, //"smartnft_create_category",
        },
      });

      fetchCatAndColl();

      setName("");

      console.log(res);
    } catch (err) {
      console.log(err);
    }
  };

  const isExist = async function (name) {
    try {
      const options = {
        type: "post",
        url: importer_local.BACKEND_AJAX_URL,
        data: {
          action: existAction, //"smartnft_category_exist",
        },
      };
      options.data[nameKey] = name;

      const res = await jQuery.ajax(options);
      console.log(res);
      setExist(res.data);
    } catch (err) {
      console.log(err);
    }
  };

  return (
    <div>
      <input
        type="text"
        placeholder={__("Enter name", SLUG)}
        onChange={(e) => {
          isExist(e.target.value.trim());
          setName(e.target.value.trim());
        }}
      />
      {!exist && name !== "" && (
        <p className="available">{escapeHTML(__("Name available", SLUG))}</p>
      )}
      {exist && (
        <p className="not-available">
          {escapeHTML(__("Name not available", SLUG))}
        </p>
      )}

      {!exist && name ? (
        <button onClick={create}>{escapeHTML(__("Create", SLUG))}</button>
      ) : null}
    </div>
  );
};

export default AddCollectionAndCategory;
