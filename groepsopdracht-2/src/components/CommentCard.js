import React from 'react';
import PropTypes from 'prop-types';

const styles = {
    marginBottom: '5px',
    marginTop: '5px',
    marginLeft: '4em',
    minHeight: '50px'
};

const CommentCard = (props) => {
    return (
        <div>
            <div className="mdl-grid">
                <div className="mdl-layout-spacer"></div>
                    <div className="mdl-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--10-col" style={styles}>
                        <div className="mdl-card__title">
                            <h2 className="mdl-card__title-text">{props.commentModel.contents}</h2>
                        </div>
                    </div>
                <div className="mdl-layout-spacer"></div>
            </div>
        </div>
    )
}

CommentCard.propTypes = {
    commentModel: PropTypes.object
}

export default CommentCard;