import React, { Component } from 'react';
import './App.css';
import { Switch, Route } from 'react-router-dom'

import Navbar from './components/Navbar';
import Login from './components/Login';
import Forum from './components/Forum';
import Message from './components/Comment';

class App extends Component {

  render() {
    return (
      <div className={"container"}>
      <Navbar/>
        <Switch>
          <Route exact path='/login' component={Login} />
          <Route path='/messages' component={Forum} />
          <Route path='/comments/:messageId' component={Message} />
        </Switch>
      </div>
    );
  }
}

export default App;
