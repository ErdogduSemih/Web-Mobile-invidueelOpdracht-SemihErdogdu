import React from 'react';
import MessagesTable from './MessagesTable';
import Search from './Search';

class Messages extends React.Component {
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
    this.setState({ searchCategory: event.target.value.substr(0, 100) });
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
      <div className="mdl-grid">
        <div className="mdl-cell mdl-cell--9-col">
          <MessagesTable filteredMessages={filteredMessages} />
        </div>
        <div className="mdl-cell mdl-cell--2-col">
          <Search
            searchId={this.state.searchId}
            searchCategory={this.state.searchCategory}
            searchTerm={this.state.searchTerm}
            onSearchCategoryChange={this.onSearchCategoryChange}
            onSearchTermChange={this.onSearchTermChange}
            onSearchIdChange={this.onSearchIdChange}
            messages={this.props.messages}
          />
        </div>
      </div>
    )
  }
}

Messages.propTypes = {

};

export default (Messages);