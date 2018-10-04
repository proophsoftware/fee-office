<?php declare(strict_types=1);
/**
 * This file is part of the prooph/event-machine-docs.
 * (c) 2018 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<style>
    body {
        font-size: 16px;
    }
    /* Header Section */
    header {
        font-size: 17px;
        font-weight: 400;
    }

    .dropdown-menu {
        font-size: 17px;
    }

    .alert-light {
        color: #818182;
        background-color: #fefefe;
    }

    .alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }

    .prooph-logo {
        float: left;
        margin-left: 8px;
        margin-right: 8px;
        transition-timing-function: ease-in-out;
        transition: all 5s;
        height: 50px;
        padding: 5px;
    }

    #forkongithub a {
        background: #000;
        color: #fff;
        text-decoration: none;
        font-family: arial, sans-serif;
        text-align: center;
        font-weight: bold;
        padding: 5px 40px;
        font-size: 1rem;
        line-height: 2rem;
        position: relative;
        transition: 0.5s;
    }

    #forkongithub a:hover {
        background: #c11;
        color: #fff;
    }

    #forkongithub a::before, #forkongithub a::after {
        content: "";
        width: 100%;
        display: block;
        position: absolute;
        top: 1px;
        left: 0;
        height: 1px;
        background: #fff;
    }

    #forkongithub a::after {
        bottom: 1px;
        top: auto;
    }

    @media screen and (min-width: 800px) {
        #forkongithub {
            position: fixed;
            display: block;
            top: 0;
            right: 0;
            width: 200px;
            overflow: hidden;
            height: 200px;
            z-index: 1000;
        }

        #forkongithub a {
            width: 200px;
            position: absolute;
            top: 80px;
            right: -60px;
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -o-transform: rotate(45deg);
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.8);
        }
    }
</style>
