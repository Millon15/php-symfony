import React, { useState } from 'react';
import Container from '@material-ui/core/Container';
import Button from '@material-ui/core/Button';
import {Link} from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import Typography from '@material-ui/core/Typography';
import Menu from '@material-ui/core/Menu';
import MenuItem from '@material-ui/core/MenuItem';
import Paper from '@material-ui/core/Paper';
import axios from 'axios';

import requestRoutes from '../config/requestRoutes';

const Home = ({ history }) => {
    const [savedGames, setSavedGames] = useState([]);
    const [anchorEl, setAnchorEl] = useState(null);

    const handleLoadGames = event => {
        setAnchorEl(event.currentTarget);
        axios.get(`${requestRoutes.allGames}`).then(response => {
            setSavedGames(response.data)
        }).catch(error => console.log(error));
    };

    const handleStartGame = userGame => {
        setAnchorEl(null);
        localStorage.setItem('currentUser', userGame.userId);
        history.push('/game');
    };

    const useStyles = makeStyles(theme => ({
        root: {
            padding: theme.spacing(3, 2),
            width: '100%',
            display: 'flex',
            flexGrow: 1,
            justifyContent: 'space-evenly',
            marginTop: '20px',
        },
        typography: {
            padding: theme.spacing(2),
        },
    }));

    const classes = useStyles();

    return (
        <div>
            <Container maxWidth="sm">
                <Paper className={classes.root}>
                    <Link to="/new-player" style={{textDecoration: 'none', color: 'black'}} className="MuiButtonBase-root MuiButton-root MuiButton-contained">New Game</Link>
                    <Button variant="contained" color="default" onClick={handleLoadGames} aria-controls="simple-menu" aria-haspopup="true">Load</Button>
                </Paper>

                <Menu
                    id="simple-menu"
                    anchorEl={anchorEl}
                    keepMounted
                    open={Boolean(anchorEl)}
                    onClose={() => setAnchorEl(null)}
                >
                    {savedGames.map(game => (
                        <MenuItem key={game.userId} onClick={() => handleStartGame(game)}>
                            <Typography variant="h6" component="h4">
                                {game.userName} ({game.createdAt})
                            </Typography>
                        </MenuItem>
                    ))}
                </Menu>
            </Container>
        </div>
    )
}

export default Home;
