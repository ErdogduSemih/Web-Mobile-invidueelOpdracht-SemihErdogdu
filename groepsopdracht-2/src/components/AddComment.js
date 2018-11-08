import React from 'react';
import PropTypes from 'prop-types';

const styles = {
    display: 'flex'
  };

const AddComment = (props) => {
    const {
        commentModelToAdd,
        onClickAddComment,
        onChangeContents
    } = props;

    return (
        <div className="section">
            <div className="mdl-grid">
                <div className="mdl-layout-spacer"></div>
                    <div className="mdl-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--10-col">
                        <div className="mdl-card__title">
                            <h2 className="mdl-card__title-text">Add Comment</h2>
                        </div>
                        
                        <div className="mdl-card__supporting-text mdl-typography--headline">
                            <div className={styles}>
                            <div id="token">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell">
                                    <input className="mdl-textfield__input" type="text" id="text-input-token" placeholder="Token..." readOnly value={commentModelToAdd.token}/>
                                </div>
                            </div>

                            <div id="comment">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell" id="comment-div">
                                <textarea className="mdl-textfield__input" type="text" rows="3" id="text-area-comment"
                                    value={commentModelToAdd.contents}
                                    onChange={onChangeContents}>
                                </textarea>
                                <label class="mdl-textfield__label" for="text-area-comment">Comment...</label>
                            </div>
                            </div>
                            </div>
                        </div>
                        <div className="mdl-card__actions mdl-card--border intro-card-actions">
                            <button className="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" id="button-comment" 
                                onClick={onClickAddComment}
                                disabled={!commentModelToAdd.contents}>
                                Comment
                            </button>
                        </div>
                    </div>
                <div className="mdl-layout-spacer"></div>
            </div>
        </div>);
}

AddComment.defaultProps = {
    commentModelToAdd: { 
        id: 0,
        contents: '',
        token: '',
        messageId: 0
    }
}

AddComment.propTypes = {
    commentModelToAdd: PropTypes.object.isRequired,
    onClickAddComment: PropTypes.func.isRequired,
    onChangeContents: PropTypes.func.isRequired
}

export default AddComment;