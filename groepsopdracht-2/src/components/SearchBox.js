import React, { Component } from 'react';
import PropTypes from "prop-types";

const SearchBox = (props) => {
    return (
        <input className="mdl-textfield__input"
            value={props.value}
            onChange={props.onChange}
            type="text"
            placeholder={props.placeholder}
            id="searchById" />
    );
}

SearchBox.propTypes = {
    xxxxx: PropTypes.string
}

export default SearchBox;