import React, { Component } from 'react';
import axios from 'axios';
import { connect } from 'react-redux';

import Row from './Row';

import LinearProgress from '@material-ui/core/LinearProgress';

import MessageCard from './MessageCard';
import CommentCard from './CommentCard';
import AddComment from './AddComment';

const styles = {
  marginBottom: '5px',
  marginTop: '5px',
  marginLeft: '4em',
  minHeight: '50px'
};

class Comment extends Component {
    constructor(props) {
      super(props);
      this.state = {
        isLoading: false,
        messageModel: {
          id: 0,
          contents: '',
          category: '',
          upvotes: 0,
          downvotes: 0
        },
        commentsModel: [],
        commentModelToAdd: {
          id: 0,
          contents: '',
          token: '',
          messageId: 0
        },
        token: ''
      };
    }

    onChangeContents = (event) => {
      const newContents = event.target.value;
      const modelToUpdate = this.state.commentModelToAdd;

      if (newContents) {
          modelToUpdate.contents = newContents;
      } else {
          modelToUpdate.contents = "";
      }

      this.setState({ commentModelToAdd: modelToUpdate });
    }
    
    onClickAddComment = () => {
        const { match: { params } } = this.props;
        const contents = this.state.commentModelToAdd.contents.trim();

        if (contents) {
            axios.post(`http://127.0.0.1:8000/message/${params.messageId}/comment/new?content=${contents}`)
            .then(res => {
              const result = res.data;

              this.setState(prevState => ({
                commentModelToAdd: {
                    ...prevState.commentModelToAdd,
                    token: result
                }
              }));
            });

            this.setState({
              commentsModel: [...this.state.commentsModel, this.state.commentModelToAdd]
            });
        }

        this.setState(prevState => ({
          commentModelToAdd: {
              ...prevState.commentModelToAdd,
              id: 0,
              contents: '',
              messageId: 0
          }
        }));
    }

    componentDidMount() {
      const { match: { params } } = this.props;
      this.setState({
        isLoading: true
      });

      axios.get(`http://127.0.0.1:8000/message/${params.messageId}`)
        .then(res => {
          const result = res.data;
          this.setState({
            messageModel: result
          });
        });

      axios.get(`http://127.0.0.1:8000/message/${params.messageId}/comment`)
        .then(res => {
          const result = res.data;
          this.setState({
            commentsModel: result
          });
        });

        this.setState({
          isLoading: false,
        });
    }

    render() {
        return(
          <div>
            <Row>
              {this.state.isLoading ? 
                <LinearProgress /> :
                  <div>
                    <MessageCard messageModel={this.state.messageModel} />
                    <AddComment commentModelToAdd={this.state.commentModelToAdd}
                        onClickAddComment={this.onClickAddComment} onChangeContents={this.onChangeContents}/>
                    {this.state.commentsModel.length ?
                        <div>
                            {this.state.commentsModel.map(commentModel => (
                                <CommentCard key={commentModel.id} commentModel={commentModel} />
                            ))}
                        </div> :
                        <div className="mdl-grid">
                          <div className="mdl-layout-spacer"></div>
                            <div className="mdl-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--10-col" style={styles}>
                              <div className="mdl-card__title">
                                  <h2 className="mdl-card__title-text">No Comments.</h2>
                              </div>
                            </div>
                          <div className="mdl-layout-spacer"></div>
                        </div>
                    }
                  </div>
              }
            </Row>
          </div>
        )
      }
}

function mapStateToProps(state, ownProps) {
  return {
    messages: state.messages
  };
}

function mapDispatchToProps() {

}

export default connect(mapStateToProps, mapDispatchToProps)(Comment)