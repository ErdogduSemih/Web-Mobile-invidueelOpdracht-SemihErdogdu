import React from 'react';
import PropTypes from 'prop-types';

const styles = {
    minHeight: '50px'
};

const MessageCard = (props) => {
    return (
        <div>
            <div className="mdl-grid">
                <div className="mdl-layout-spacer"></div>
                    <div className="mdl-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--10-col" style={styles}>
                        <div className="mdl-card__title">
                            <h2 className="mdl-card__title-text">{props.messageModel.contents}</h2>
                        </div>
                        <div className="mdl-card__supporting-text">
                            <span className="mdl-chip">
                                <span className="mdl-chip__text">{props.messageModel.category}</span>
                            </span>
                        </div>
                        <div className="mdl-card__actions mdl-card--border intro-card-actions">
                            <button className="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" id="button-upvote" >
                            <i className="material-icons">thumb_up</i> Upvote
                            </button>
                            <button className="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" id="button-upvote" >
                            <i className="material-icons">thumb_down</i> Downvote
                            </button>
                        </div>
                    </div>
                <div className="mdl-layout-spacer"></div>
            </div>
        </div>
    )
}

MessageCard.propTypes = {
    messageModel: PropTypes.object
}

export default MessageCard;