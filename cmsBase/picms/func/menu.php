<!-- START MENU SIDEBAR WRAPPER -->
<aside class="sidebar sidebar-left">
	<div class="sidebar-content">
		<div class="aside-toolbar" style="background-color:#fff !important">
			<ul class="site-logo">
				<li>
					<!-- START LOGO -->
					<a href="index.php">
						<div class="logo">
							<img src="../picms/assets/images/logo.png">
						</div>
					</a>
					<!-- END LOGO -->
				</li>
			</ul>
		</div>
		<nav class="main-menu">
			<ul class="nav metismenu">
				
				<?php if ($_SESSION['user']['UserTypeID'] == 1) { ?>
					<li
						class="nav-dropdown <?php echo ($urlPage == 'template' || $urlPage == 'template-list' || $urlPage == 'template-content' || $urlPage == 'template-content-list' || $urlPage == 'template-content-home' || $urlPage == 'template-content-buttons' || $urlPage == 'template-content-text' || $urlPage == 'template-content-product-line' || $urlPage == 'template-content-home-feature' || $urlPage == 'template-content-step-by-step' ? 'active' : 'active'); ?>">
						<a class="has-arrow" href="#" aria-expanded="true"><i
								class="icon dripicons-meter"></i><span>CONTEÚDO</span></a>
						<ul class=" nav-sub">
							<li id="template-list" <?php echo ($urlPage == 'template-list' ? 'class="active"' : ''); ?>><a
									href="template-list.php"><span>Criar páginas (templates)</span></a></li>
							<li id="template-content-list" <?php echo ($urlPage == 'template-content-text' || (isset($pageTypeID) && $pageTypeID == "3") ? 'class="active"' : ''); ?>><a
									href="template-content-list.php?pageTypeID=3"><span>Conteúdo páginas de Texto (feature produtos)</span></a></li>
							<li id="template-content-list" <?php echo ($urlPage == 'template-content-step-by-step' || (isset($pageTypeID) && $pageTypeID == "8") ? 'class="active"' : ''); ?>><a
									href="template-content-list.php?pageTypeID=8"><span>Conteúdo Step By Step (eureka)</span></a></li>
							<li id="template-content-list" <?php echo ($urlPage == 'template-content-home-feature' || (isset($pageTypeID) && $pageTypeID == "7,12") ? 'class="active"' : ''); ?>><a
									href="template-content-list.php?pageTypeID=7,12"><span>Conteúdo Vídeo Features (eureka)</span></a></li>
							<li id="template-content-list" <?php echo ($urlPage == 'template-content-product-line' || (isset($pageTypeID) && $pageTypeID == "4") ? 'class="active"' : ''); ?>><a
									href="template-content-list.php?pageTypeID=4"><span>Botões de Produto</span></a></li>
							<li id="template-content-list" <?php echo ($urlPage == 'template-content-buttons' || (isset($pageTypeID) && $pageTypeID == "0,2,6") ? 'class="active"' : ''); ?>><a
									href="template-content-list.php?pageTypeID=0,2,6"><span>Botões Categorias</span></a></li>
							<li id="template-content-list" <?php echo ($urlPage == 'template-content-home' || (isset($pageTypeID) && $pageTypeID == "1,5") ? 'class="active"' : ''); ?>><a
									href="template-content-list.php?pageTypeID=1,5"><span>Home - Screensavers</span></a></li>
							
						</ul>
					</li>
				<?php } ?>
				<li
					class="nav-dropdown <?php echo ($urlPage == 'operator-user' || $urlPage == 'operator-user-list' || $urlPage == 'operator-template' || $urlPage == 'operator-template-list' || $urlPage == 'device' || $urlPage == 'device-list' || $urlPage == 'operator-plan' || $urlPage == 'operator-plan-list' || $urlPage == 'operator-value' || $urlPage == 'operator-value-list' ? 'active' : ''); ?>">
					<a class="has-arrow" href="#" aria-expanded="false"><i class="icon dripicons-meter"></i><span>CONTEÚDO
							OPERADORAS</span></a>
					<ul class=" nav-sub">
					<li id="template-user-list" <?php echo ($urlPage == 'template-user' || $urlPage == 'template-user-list' ? 'class="active"' : ''); ?>><a href="template-user-list.php"><span>Usuários p/ operadoras</span></a></li>	
					<li id="operator-user-list" <?php echo ($urlPage == 'operator-user' || $urlPage == 'operator-user-list' ? 'class="active"' : ''); ?>><a href="operator-user-list.php"><span>Usuários</span></a></li>
						<li id="operator-template-list" <?php echo ($urlPage == 'operator-template' || $urlPage == 'operator-template-list' ? 'class="active"' : ''); ?>><a
								href="operator-template-list.php"><span>Template</span></a></li>
						<?php if ($_SESSION['user']['UserTypeID'] == 1) { ?>
							<li id="device-list" <?php echo ($urlPage == 'device' || $urlPage == 'device-list' ? 'class="active"' : ''); ?>><a href="device-list.php"><span>Aparelhos</span></a></li>
						<?php } ?>
						<li id="operator-plan-list" <?php echo ($urlPage == 'operator-plan' || $urlPage == 'operator-plan-list' ? 'class="active"' : ''); ?>><a href="operator-plan-list.php"><span>Planos</span></a></li>
						<li id="operator-value-list" <?php echo ($urlPage == 'operator-value' || $urlPage == 'operator-value-list' ? 'class="active"' : ''); ?>><a href="operator-value-list.php"><span>Valores</span></a></li>
					</ul>
				</li>
				<?php if ($_SESSION['user']['UserTypeID'] == 1) { ?>
					<li
						class="nav-dropdown <?php echo ($urlPage == 'update-site' || $urlPage == 'update-tablet' ? 'active' : 'active'); ?>">
						<a class="has-arrow" href="#" aria-expanded="true"><i
								class="icon dripicons-meter"></i><span>ATUALIZAÇÃO</span></a>
						<ul class="collapse nav-sub">
							<li id="update-site" <?php echo ($urlPage == 'update-site' ? 'class="active"' : ''); ?>><a
									href="update-site.php"><span>Atualizar Site</span></a></li>
							<li id="update-tablet" <?php echo ($urlPage == 'update-tablet' ? 'class="active"' : ''); ?>><a
									href="update-tablet.php"><span>Atualizar Tablet</span></a></li>
						</ul>
					</li>
				<?php } ?>
				<?php if ($_SESSION['user']['UserTypeID'] == 1) { ?>
					<li class="nav-dropdown <?php echo ($urlPage == 'navigation-control' ? 'active' : ''); ?>">
						<a class="has-arrow" href="#" aria-expanded="true"><i
								class="icon dripicons-meter"></i><span>RELATÓRIO</span></a>
						<ul class="collapse nav-sub">
							<li id="navigation-control" <?php echo ($urlPage == 'navigation-control' ? 'class="active"' : ''); ?>><a
									href="navigation-control.php"><span>Registro de Navegação</span></a></li>
						</ul>
					</li>
				<?php } ?>
				<?php if ($_SESSION['user']['UserTypeID'] == 1) { ?>
					<li class="sidebar-header"><span>ADMIN</span></li>
					<li
						class="nav-dropdown <?php echo ($urlPage == 'lista-usuarios' || $urlPage == 'perfil' || $urlPage == 'unity-list' || $urlPage == 'unity' ? 'active' : ''); ?>">
						<a class="has-arrow" href="#" aria-expanded="false"><i
								class="icon dripicons-meter"></i><span>ADMINISTRADOR</span></a>
						<ul class="collapse nav-sub">
							<li id="lista-usuarios" <?php echo ($urlPage == 'lista-usuarios' ? 'class="active"' : ''); ?>><a
									href="lista-usuarios.php"><span>Usuários</span></a></li>
							<li id="perfil" <?php echo ($urlPage == 'perfil' ? 'class="active"' : ''); ?>><a
									href="perfil.php?id=<?php echo $_SESSION["user"]["UserID"]; ?>"><span>Seu perfil</span></a></li>
							<!--<li id="unity-list" <?php echo ($urlPage == 'unity-list' || $urlPage == 'unity' ? 'class="active"' : ''); ?>><a href="unity-list.php"><span>Unidades</span></a></li>-->
						</ul>
					</li>
				<?php } ?>
			</ul>
		</nav>
	</div>
</aside>
<!-- END MENU SIDEBAR WRAPPER -->