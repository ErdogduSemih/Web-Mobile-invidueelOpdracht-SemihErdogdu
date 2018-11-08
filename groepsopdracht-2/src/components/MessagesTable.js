import React from 'react';

import Link from 'react-router-dom/Link';

import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import Paper from '@material-ui/core/Paper';
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import Button from '@material-ui/core/Button';
import ThumbUp from '@material-ui/icons/ThumbUp';
import ThumbDown from '@material-ui/icons/ThumbDown';

import axios from 'axios';

const styles = theme => ({
  root: {
    width: '90%',
    marginTop: theme.spacing.unit * 3,
    overflowX: 'auto',
    marginLeft: 'auto',
    marginRight: 'auto',
  },
  table: {
    width: '100%',
  },
  tr: {
    textAlign: 'left',
  },
  link: {
    color: '#3f51b5',
    textDecoration: 'none'
  },
  voteButton: {
    color: '#3f51b5',
  }
});

class MessagesTable extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      messages: props.messages,
      searchId: '',
      searchTerm: '',
      searchCategory: '',
      classes: props
    };
  }

  onSearchIdChange = (event) => {
    this.setState({ searchId: event.target.value.substr(0, 10) });
  }

  onSearchTermChange = (event) => {
    this.setState({ searchTerm: event.target.value.substr(0, 1000) });
  }

  onSearchCategoryChange = (event) => {
    this.setState({ searchCategory: event.target.value.substr(0, 10) });
  }

  render() {
    let filteredMessages = [];

    if (this.state.searchId !== '') {
      filteredMessages = this.props.messages.filter(
        (message) => {
          let messageId = message.id + "";
          let searchId = this.state.searchId + "";
          let returnMessage = "";
          if (messageId === searchId) {
            console.log("message id : " + messageId);
            console.log("searchid : " + searchId)
            returnMessage = message;
          }
          return returnMessage;
        }
      );
    } else if (this.state.searchTerm !== '') {
      filteredMessages = this.props.messages.filter(
        (message) => {
          return message.contents.indexOf(this.state.searchTerm) !== -1;
        }
      );
    } else if (this.state.searchCategory !== '') {
      filteredMessages = this.props.messages.filter(
        (message) => {
          return message.category.indexOf(this.state.searchCategory) !== -1;
        }
      );
    } else {
      filteredMessages = this.props.messages;
    }

    return (
      <div>
        <Paper className={this.props.classes.root}>
          <Table className={this.props.classes.table}>
            <TableHead>
              <TableRow>
                <TableCell>Id</TableCell>
                <TableCell>Content</TableCell>
                <TableCell>Category</TableCell>
                <TableCell>Upvotes</TableCell>
                <TableCell>Downvotes</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {this.props.filteredMessages.map(message => {
                return (
                  <TableRow key={message.id}>
                    <TableCell className={this.props.classes.tr} numeric>{message.id}</TableCell>
                    <Link to="/comments"><TableCell className={this.props.classes.tr} numeric>{message.contents}</TableCell></Link>
                    <TableCell className={this.props.classes.tr} numeric>{message.category}</TableCell>
                    <TableCell className={this.props.classes.tr} numeric>
                      <Button
                        id="upvote-button"
                        size="small"
                        className={this.props.classes.voteButton}
                        onClick={() => onClickUpvote(message.id)}>
                        <span>{message.upvotes}</span>
                        <ThumbUp fontSize="small"/>
                      </Button>
                    </TableCell>
                    <TableCell className={this.props.classes.tr} numeric>
                      <Button
                        id="downvote-button"
                        size="small"
                        className={this.props.classes.voteButton}
                        onClick={() => onClickDownvote(message.id)}>
                        <span>{message.downvotes}</span>
                        <ThumbDown fontSize="small"/>
                      </Button>
                    </TableCell>
                  </TableRow>
                );
              })}
            </TableBody>
          </Table>
        </Paper>
      </div>
    )
  }
}

function onClickUpvote(id) {

  axios.get(`http://127.0.0.1:8000/message/${id}/upvote`)
    .then(res => {
      const result = res.data;

    });

  console.log("upvote test");
}
function onClickDownvote(id) {

  axios.get(`http://127.0.0.1:8000/message/${id}/downvote`)
    .then(res => {
      const result = res.data;

    });

  console.log("downvote");
}


MessagesTable.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(MessagesTable);