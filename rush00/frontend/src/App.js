import React, {useState, useEffect} from 'react';
import { withRouter, Route, Switch } from "react-router";

import './App.css';
import Home from './pages/Home';
import NewPlayer from './pages/NewPlayer';
import Fight from './pages/Fight';
import Game from './pages/Game';
import Galery from './pages/Galery';

const App = () => {
  const [userName, setUserName] = useState('');

  useEffect(() => {
    const name = localStorage.getItem('currentUser');
    if (name) {
      setUserName(userName);
    }
  }, [userName]);

  return (
    <div className="App">
      <header className="App-header">
        <Switch>
          <Route exact path="/" component={Home} />
          <Route path="/new-player" component={NewPlayer} />
          <Route path="/fight" component={Fight} />
          <Route path="/game" component={Game} />
          <Route path="/world-map" component={Galery} />
        </Switch>
      </header>
    </div>
  );
}

export default withRouter(App);
