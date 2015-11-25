var apiURL = '/board'

var boggle = new Vue({

    el: '#app',

    data: {
        play: false,
        currentSquare: 0,
        board: null,
        word: null,
        used: [],
        token: null
    },

    created: function () {
        this.fetchData()
    },

    watch: {
        selectSquare: 'fetchData'
    },

    methods: {
        fetchData: function () {
            var xhr = new XMLHttpRequest()
            var self = this
            xhr.open('GET', apiURL)
            xhr.onload = function () {
                self.board = JSON.parse(xhr.responseText)
            }
            xhr.send()
        },

        selectSquare: function (id, active, used) {
            if(active && !used) {
                this.play = true
                var self = this
                for (i = 0; i < this.board.length; i++) {
                    var square = this.board[i];
                    if (square.id === id) {
                        this.currentSquare = square.id;
                        var adjacent = square.adjacent;
                        break;
                    }
                }

                this.used.push(square.id);

                if (this.word) {
                    this.word = this.word.concat(square.letter)
                } else {
                    this.word = square.letter
                }
                for (index = 0; index < this.board.length; index++) {
                    this.board[index].active = false;
                }
                for (i = 0; i < this.used.length; i++) {
                    var id = this.used[i];
                    for (index = 0; index < this.board.length; index++) {
                        if (this.board[index].id == id) {
                            this.board[index].used = true;
                            break;
                        }
                    }
                }


                for (i = 0; i < adjacent.length; i++) {
                    var id = adjacent[i];
                    for (index = 0; index < this.board.length; index++) {
                        if (this.board[index].id == id) {
                            this.board[index].active = true;
                        }
                    }
                }
            }
        },

        getNewTable: function () {
            this.clearWord();
            this.fetchData();
            var xhr = new XMLHttpRequest()
            var self = this
            xhr.open('DELETE', '/board')

            xhr.setRequestHeader("X-CSRF-TOKEN",this.token)
            xhr.send(this.word)
            this.clearWord();
        },

        saveWord: function(){
            var xhr = new XMLHttpRequest()
            var self = this

            data = JSON.stringify({ "Word": this.word});
            xhr.open('POST', '/words')

            xhr.setRequestHeader("X-CSRF-TOKEN",this.token)
            xhr.send(data)
            this.clearWord();
        },

        clearWord: function () {
            this.word = null
            this.play = false
            this.used = []

            for (i = 0; i < this.board.length; i++) {
                this.board[i].active = true;
                this.board[i].used = false;
            }
        }


    },
});