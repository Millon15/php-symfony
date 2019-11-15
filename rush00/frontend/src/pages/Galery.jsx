import React, {useEffect, useState} from 'react';
import {Link} from 'react-router-dom';
import Container from '@material-ui/core/Container';
import { Typography } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';
import Paper from '@material-ui/core/Paper';
import AppBar from '@material-ui/core/AppBar';
import Button from '@material-ui/core/Button';
import axios from 'axios';

import requestRoutes from '../config/requestRoutes';
import VictoryIcon from '../assets/VictoryIcon';

const mock = [{poster: 'https://eu.movieposter.com/posters/archive/main/22/A70-11370', name: 'Gladiator', isDefeated: false, id: '12456'}, {id: '11', poster: 'https://eu.movieposter.com/posters/archive/main/38/MPW-19355', name: 'Forest Gump', isDefeated: true}, {id: '222', poster: 'https://eu.movieposter.com/posters/archive/main/38/MPW-19355', name: 'Forest Gump', isDefeated: true}, {id: '33', poster: 'https://eu.movieposter.com/posters/archive/main/38/MPW-19355', name: 'Forest Gump', isDefeated: true}, {id: '44', poster: 'https://eu.movieposter.com/posters/archive/main/38/MPW-19355', name: 'Forest Gump', isDefeated: false}, {id: '555', poster: 'https://eu.movieposter.com/posters/archive/main/38/MPW-19355', name: 'Forest Gump', isDefeated: true}, {id: '66', poster: 'https://eu.movieposter.com/posters/archive/main/38/MPW-19355', name: 'Forest Gump', isDefeated: false}, {id: '77', poster: 'https://eu.movieposter.com/posters/archive/main/5/A70-2978', name: 'Good felows', isDefeated: true}, {id: '88', poster: 'https://eu.movieposter.com/posters/archive/main/7/A70-3860', name: '007', isDefeated: false}, {id: '899', poster: 'https://eu.movieposter.com/posters/archive/main/13/MPW-6725', name: 'Sound of music', isDefeated: false}];

const useStyles = makeStyles(theme => ({
    root: {
      padding: theme.spacing(3, 2),
    },
    appBar: {
        marginBottom: '20px',
        padding: '16px 0',
    },
    title: {
        maxWidth: '200px',
        wordBreak: 'all',
    }
  }));

const Galery = ({ history }) => {
    const [info, setInfo] = useState(mock);

    const classes = useStyles();

    useEffect(() => {
        if (!localStorage.getItem('currentUser')) {
            history.push('/');
        } else {
            // api call for all movies
            axios.get(`${requestRoutes.userProgress}${localStorage.getItem('currentUser')}`).then(res => {
                setInfo(res.data.movies)
            }).catch(err => {
                console.log(err);
                setInfo(mock);
            });
        }
    }, [history]);

    return (
        <Container maxWidth="sm" className="worldMap">
            <AppBar position="static" className={classes.appBar}>
                <Link to="/game" className="customLink">
                    <Button color="inherit">Back to game</Button>
                </Link>
            </AppBar>
            {info.map(movie => (
                <div key={movie.id} className="moviemonsterItem">
                    <Paper className={classes.root}>
                        <div className="moviemonsterHolder">
                            <img src={movie.poster} alt={movie.name} className={`moviemonsterPic ${movie.isDefeated ? '' : 'notDefeated'}`} />
                            {movie.isDefeated && (
                                <span className="victory">
                                    <VictoryIcon />
                                </span>
                            )}
                        </div>
                        <Typography variant="h5" component="h3" className={classes.title}>
                            {movie.name}
                        </Typography>
                    </Paper>
                </div>
            ))}
        </Container>
    )
}

export default Galery;
