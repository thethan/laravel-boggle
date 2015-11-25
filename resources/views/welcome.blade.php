<!DOCTYPE html>
<html>
<head>
    <title>Boggle</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{!! asset('css/app.css') !!}">
    <meta>
    <style>
        .container {
            max-height:800px;
            max-width: 800px;
        }
        ul {
            flex-direction: row;
            flex-wrap: wrap ;
            /*width:60em;*/

            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-flow: row wrap;
            max-width:80%;
            align-self: center;
            justify-content: center;

        }
        ul li {
            flex-grow: 1;
            list-style: none;
            width: 22%;
            height: 22%;
            margin: 3em 3% 0 0;
            color: #fff;
            display: flex;
            background: #ff6347;
            align-items: center;
            justify-content: center;
        }
        li p {

            font-weight: 700;
            font-size: 4em;
        }
        .container.play li {
            background: rgba(220,220,0,.4);
        }
        .container.play li.active {
            background: rgba(255,91,71,1);
        }
        .container.play li.used {
            background: rgba(59, 90, 255, 1);
        }

    </style>
</head>
<body>
    <div id="app" class="container">
        <input v-model="token" type="hidden" name="_token" value="{{ csrf_token() }}">

        <h1>@{{ word }}</h1>
        <button @click="saveWord">Save Word</button>
        <button @click="clearWord">Clear Word</button>
        <button @click="getNewTable">New Tables</button>
        <ul class="container" v-bind:class="{'play': play }">
            <li
                @click="selectSquare(square.id, square.active, square.used)"
                v-bind:class="{'used':  square.used , 'active' : square.active }" id="square-@{{ square.id }}" v-for="square in board">
                <p>@{{ square.letter }}</p>
            </li>
        </ul>
    </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.10/vue.js"></script>
<script src="/js/app.js"></script>
</html>
