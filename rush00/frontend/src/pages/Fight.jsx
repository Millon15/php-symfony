import React, {useEffect, useState} from 'react';
import Container from '@material-ui/core/Container';
import { Button } from '@material-ui/core';
import Modal from '@material-ui/core/Modal';
import { makeStyles } from '@material-ui/core/styles';
import { Typography } from '@material-ui/core';
import Paper from '@material-ui/core/Paper';
import axios from 'axios';

import requestRoutes from '../config/requestRoutes';

const Fight = ({ history }) => {
    const [playerInfo, setPlayerInfo] = useState({damage: 1, hp: 3});
    const [enemyInfo, setEnemyInfo] = useState({id: '123', damage: 1, hp: 3, poster: 'https://images.unsplash.com/photo-1508161773455-3ada8ed2bbec?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=800&q=60'});

    const [playerDeath, showPlayerDeath] = useState(false);
    const [enemyDeath, showEnemyDeath] = useState(false);

    useEffect(() => {
        const currentUser = localStorage.getItem('currentUser');
        if (!currentUser) {
            history.push('/');
        } else {
            axios
                .get(requestRoutes.fight, {params: {userId: currentUser}})
                .then(response => {
                    if (response.data.isWinner) {
                        history.push('/game');
                    }
                    setPlayerInfo(response.data.userInfo);
                    setEnemyInfo(response.data.enemy);
                })
                .catch(error => console.log(error));
        }
    }, [history])

    const handleFight = () => {
        const chance = Math.floor(Math.random() * 2) + 1;

        if (chance === 1) {
            setPlayerInfo(prevInfo => {
                if (prevInfo.hp - enemyInfo.damage <= 0) {
                    showPlayerDeath(true);
                }
                return {hp: prevInfo.hp - enemyInfo.damage, damage: prevInfo.damage};
            });
        } else {
            setEnemyInfo(prevInfo => {
                if (prevInfo.hp - playerInfo.damage <= 0) {
                    showEnemyDeath(true);
                    axios
                        .post(requestRoutes.endOfGame, {userId: localStorage.getItem('currentUser'), movieId: enemyInfo.id,})
                        .catch(error => console.log(error));
                }
                return {id: prevInfo.id, poster: prevInfo.poster, hp: prevInfo.hp - playerInfo.damage, damage: playerInfo.damage};
            });
        }
    }

    const useStyles = makeStyles(theme => ({
        paper: {
          position: 'absolute',
          width: 400,
          backgroundColor: theme.palette.background.paper,
          boxShadow: theme.shadows[5],
          padding: theme.spacing(2, 4, 3),
        },
        root: {
            padding: theme.spacing(3, 2),
        },
    }));
    const classes = useStyles();

    const handleLeave = () => history.push('/game');

    return (
    <Container maxWidth="sm">
        <div className="fightContainer">
            <div className="player">
                <Paper className={classes.root}>
                    <img src="https://images.unsplash.com/photo-1475874619827-b5f0310b6e6f?ixlib=rb-1.2.1&auto=format&fit=crop&w=733&q=80" alt="my-face"/>
                    <Typography>
                        My HP: {playerInfo.hp}
                    </Typography>
                    <Typography>
                        My strength: {playerInfo.damage}
                    </Typography>
                </Paper>
            </div>
            <div className="actions">
                <Button onClick={handleFight} color="secondary" variant="contained">Fight</Button>
                <Button color="primary" variant="contained" onClick={handleLeave}>Leave</Button>
            </div>
            <div className="enemy">
                <Paper className={classes.root}>
                    <img src={enemyInfo.poster} alt="enemy-face"/>
                    <Typography>
                        Enemy HP: {enemyInfo.hp}
                    </Typography>
                    <Typography>
                        Enemy strength: {enemyInfo.damage}
                    </Typography>
                </Paper>
            </div>
        </div>

        <Modal
            aria-labelledby="simple-modal-title"
            aria-describedby="simple-modal-description"
            open={playerDeath}
            onClose={() => {
                localStorage.removeItem('currentUser');
                history.push('/');
            }}
        >
            <div className={classes.paper} style={{top: '50%', left: '50%', transform: 'translate(-50%, -50%)', textAlign: 'center'}}>
                <Typography>Unfortunately you died.</Typography>
                <Typography>Game over.</Typography>
            </div>
        </Modal>

        <Modal
            aria-labelledby="simple-modal-title"
            aria-describedby="simple-modal-description"
            open={enemyDeath}
            onClose={() => history.push('/game')}
        >
            <div className={classes.paper} style={{top: '50%', left: '50%', transform: 'translate(-50%, -50%)', textAlign: 'center'}}>
                <Typography>Huray! You win.</Typography>
            </div>
        </Modal>
    </Container>
    )
};

export default Fight;
