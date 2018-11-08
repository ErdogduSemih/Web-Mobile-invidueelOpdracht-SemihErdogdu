import React, { Component } from 'react';
import PropTypes from "prop-types";
import MessagesTable from "./Messages"
import { connect } from "react-redux";
import { bindActionCreators } from "redux";
import axios from 'axios';
import { storeMessages } from '../actions/messageActions'
import { LinearProgress } from '@material-ui/core';

class Forum extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoading: false,
            messages: []
        };
    }
    
    componentDidMount() {
        this.setState({
            isLoading: true
        })

        axios.get('http://127.0.0.1:8000/message')
            .then(res => {
                const messages = res.data;
                this.setState({
                    isLoading: false,
                    messages: messages
                });
                for (let message of messages) {
                    this.props.storeMessages(message);
                    }
            })
    }

    render() {
        return (
            <div>
                { this.state.isLoading ? <LinearProgress/> :
                    <MessagesTable messages={this.state.messages}/>
                }

            </div>
        )
    }

}

Forum.propTypes = {
    messages: PropTypes.string
}

function mapStateToProps(state) {
    return {};
}

function mapDispatchToProps(dispatch, ownProps) {
    return {
        storeMessages: bindActionCreators(storeMessages, dispatch)
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(Forum);