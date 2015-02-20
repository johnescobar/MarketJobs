  <div id="GaleriaProducto">

      <form name="frmpro" id="frmpro" action="?mod=<?php echo SIMReg::get( "mod" )?>" method="post" class="formvalida" enctype="multipart/form-data">

          <?php
          $action = "InsertarGaleria";

          if( $_GET["IDFotoProducto"] )
          {
                  $EditProducto =$dbo->fetchAll("FotoProducto"," IDFotoProducto = '" . $_GET["IDFotoProducto"] . "' ","array");
                  $action = "ModificaGaleria";
                  ?>
                  <input type="hidden" name="IDFotoProducto" id="IDFotoProducto" value="<?php echo $EditProducto["IDFotoProducto"]?>" />
                  <?php
          }
          ?>
          <table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
          <tr>
                  <th colspan="2">Galeria Producto</th>
          </tr>
          <tr>
                  <td> Nombre </td>
                  <td><input id="Nombre" type="text" size="25" title="Nombre" name="Nombre" class="input mandatory" value="<?php echo $EditProducto["Nombre"] ?>" /> </td>
          </tr>
          <tr>
              <td> Galeria </td>
              <td>
              <?php
              if($EditProducto["Foto"])
              {
              ?>
                  <a href="<?php echo PRODUCTO_ROOT.$EditProducto["Foto"]?>"><?php echo $EditProducto["Foto"] ?></a>
                  <a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&action=DelDocNot&id=".$frm[ $key ]."&idd=" .$EditProducto["IDFotoProducto"]?>"><img src='images/trash.png' border='0'></a>
              <?php
              }
              else
              {
              ?>
                  <input type="file" name="Foto" id="Foto" class="popup" title="Foto">
              <?php
              }
              ?>                            
              </td>
          </tr>
          <tr>
                  <td align="center"><input type="submit" class="submit" value="Enviar"> </td>
          </tr>
          </table>
          <input type="hidden" name="IDProducto" id="IDProducto" value="<?php echo $frm[ $key ]?>" />
          <input type="hidden" name="action" id="action" value="<?php echo $action?>" />
      </form>
      <br />
      <table class="adminlist" width="100%">
              <tr>
                      <th class="title" colspan="13"><?php echo strtoupper( "Link" ) . ": Listado"?></th>
              </tr>
              <tr>
                      <th align="center" valign="middle" width="64">Editar</th>
                      <th>Foto</th>
                      <th align="center" valign="middle" width="64">Eliminar</th>
              </tr>
              <tbody id="listacontactosanunciante">
              <?php

                      $r_documento =& $dbo->all( "FotoProducto" , "IDProducto = '" . $frm[$key]  ."'");

                      while( $r = $dbo->object( $r_documento ) )
                      {
              ?>

              <tr class="<?php echo SIMUtil::repetition() ? 'row0' : 'row1'?>">
                      <td align="center" width="64">
                              <a href="<?php echo "?mod=" . SIMReg::get( "mod" ) . "&action=edit&id=" . $_GET[id] ."&IDFotoProducto=".$r->IDFotoProducto."#GaleriaProducto"?>"><img src='images/edit.png' border='0'></a>                                </td>
                      <td><img src="<?php echo PRODUCTO_ROOT.$r->Foto?>" width="100" border="0"></td>
                      <td align="center" width="64">
                              <a href="?mod=<?php echo SIMReg::get( "mod" )?>&action=EliminaGaleria&id=<?php echo $frm[ $key ];?>&IDFotoProducto=<? echo $r->IDFotoProducto ?>"><img src='images/trash.png' border='0' /></a>                                </td>
              </tr>
              <?php
              }
              ?>
              </tbody>
              <tr>
                      <th class="texto" colspan="13"></th>
              </tr>
      </table>



</div>
