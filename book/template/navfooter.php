<?php declare(strict_types=1);
/**
 * This file is part of the proophsoftware/fee-office.
 * (c) 2018 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$prev = $this->page->getPrev();
$parent = $this->page->getParent();
$next = $this->page->getNext();

if (! ($copyright = $this->page->getCopyright())) {
    $copyright = '<a href="http://prooph-software.de/about.html">Imprint</a> | Powered by <a href="https://github.com/tobiju/bookdown-bootswatch-templates" title="Visit project to generate your own docs">Bookdown Bootswatch Templates</a>.';
}
?>
        </div>
    </div>
</div>

<footer>
    <div class="links">
        <div class="container">
            <div class="row">
                <div class="prev col-xs-6">
                    <?php if ($prev):; ?>
                        <?php echo $this->anchorRaw($prev->getHref(), 'Previous'); ?>
                    <?php endif; ?>
                </div>
                <div class="next col-xs-6">
                    <?php if ($next):; ?>
                        <?php echo $this->anchorRaw($next->getHref(), 'Next'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><?php echo $copyright; ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>
