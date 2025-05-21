<div x-data="{ show: false }" x-init="
    window.addEventListener('scroll', () => {
        show = window.scrollY > 100;
    });
">
    <a href="#" class="dhtt-back-to-top"
       @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })"
       x-transition.duration.500ms
       x-show="show"
    >
        <i class="fa-solid fa-chevron-up"></i>
    </a>

</div>

<footer id='dhtt-footer' class="py-1">
    <div class="container mx-auto">
        <p class="py-0 text-center text-white!">Copyright &copy; <?php echo date("Y"); ?> Csíkszéki Erdélyi
            Kárpát-Egyesület</p>
    </div>
</footer>
