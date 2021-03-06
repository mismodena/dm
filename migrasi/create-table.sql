/****** Object:  Table [dbo].[gudang_user]    Script Date: 07/11/2017 15:52:13 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[gudang_user](
	[user_id] [nvarchar](50) NOT NULL,
	[gudang] [varchar](50) NOT NULL,
	[aktif] [bit] NULL,
 CONSTRAINT [PK_gudang_user] PRIMARY KEY CLUSTERED 
(
	[user_id] ASC,
	[gudang] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[order_split]    Script Date: 07/11/2017 15:52:13 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[order_split](
	[order_id] [varchar](50) NOT NULL,
	[order_id_split] [varchar](50) NOT NULL,
	[user_id] [varchar](50) NOT NULL,
	[tanggal] [datetime] NULL,
	[dealer_id] [varchar](50) NULL,
	[gudang] [varchar](10) NULL,
	[diskon_nominal] [float] NULL,
	[diskon] [float] NULL,
	[kirim] [tinyint] NULL,
	[keterangan_order] [text] NULL,
	[pengajuan_diskon] [tinyint] NULL,
 CONSTRAINT [PK_order_split] PRIMARY KEY CLUSTERED 
(
	[order_id] ASC,
	[order_id_split] ASC,
	[user_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'nominal diskon per faktur' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_split', @level2type=N'COLUMN',@level2name=N'diskon_nominal'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'persentase diskon per faktur' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_split', @level2type=N'COLUMN',@level2name=N'diskon'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'flag untuk pengajuan diskon atau tidak' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_split', @level2type=N'COLUMN',@level2name=N'pengajuan_diskon'
GO
/****** Object:  Table [dbo].[order_item_split]    Script Date: 07/11/2017 15:52:13 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[order_item_split](
	[order_id] [varchar](50) NOT NULL,
	[order_id_split] [varchar](50) NOT NULL,
	[user_id] [varchar](50) NOT NULL,
	[item_seq] [smallint] NOT NULL,
	[item_id] [varchar](100) NULL,
	[harga] [float] NULL,
	[kuantitas] [int] NULL,
	[diskon_default] [float] NULL,
	[diskon] [float] NULL,
	[tambahan_diskon] [float] NULL,
	[diskon_total] [float] NULL,
	[diskon_total_persen] [float] NULL,
	[keterangan_order_item] [text] NULL,
	[paketid] [varchar](50) NULL,
	[urutan_parameter] [tinyint] NULL,
 CONSTRAINT [PK_order_item_split] PRIMARY KEY CLUSTERED 
(
	[order_id] ASC,
	[order_id_split] ASC,
	[user_id] ASC,
	[item_seq] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'diskon bernilai total akumulasi kuantitas item, yang berasal dari campaign. Nilai Default dari perhitungan otomatis berdasarkan formula PM' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_item_split', @level2type=N'COLUMN',@level2name=N'diskon_default'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'diskon bernilai total akumulasi kuantitas item, yang berasal dari campaign' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_item_split', @level2type=N'COLUMN',@level2name=N'diskon'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'tambahan diskon total akumulasi yang berasal dari diskon tambahan setelah campaign' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_item_split', @level2type=N'COLUMN',@level2name=N'tambahan_diskon'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'akumulasi diskon_campaign + tambahan_diskon (satuan persen dari net price)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_item_split', @level2type=N'COLUMN',@level2name=N'diskon_total'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'persentase dari diskon_total' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'order_item_split', @level2type=N'COLUMN',@level2name=N'diskon_total_persen'
GO
/****** Object:  Default [DF_order_item_split_diskon_default]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split] ADD  CONSTRAINT [DF_order_item_split_diskon_default]  DEFAULT ((0)) FOR [diskon_default]
GO
/****** Object:  Default [DF_order_item_split_diskon]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split] ADD  CONSTRAINT [DF_order_item_split_diskon]  DEFAULT ((0)) FOR [diskon]
GO
/****** Object:  Default [DF_order_item_split_tambahan_diskon]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split] ADD  CONSTRAINT [DF_order_item_split_tambahan_diskon]  DEFAULT ((0)) FOR [tambahan_diskon]
GO
/****** Object:  Default [DF_order_item_split_diskon_total]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split] ADD  CONSTRAINT [DF_order_item_split_diskon_total]  DEFAULT ((0)) FOR [diskon_total]
GO
/****** Object:  Default [DF_order_item_split_diskon_total_persen]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split] ADD  CONSTRAINT [DF_order_item_split_diskon_total_persen]  DEFAULT ((0)) FOR [diskon_total_persen]
GO
/****** Object:  Default [DF_order_split_tanggal]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_split] ADD  CONSTRAINT [DF_order_split_tanggal]  DEFAULT (getdate()) FOR [tanggal]
GO
/****** Object:  Default [DF_order_split_diskon_nominal]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_split] ADD  CONSTRAINT [DF_order_split_diskon_nominal]  DEFAULT ((0)) FOR [diskon_nominal]
GO
/****** Object:  Default [DF_order_split_diskon]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_split] ADD  CONSTRAINT [DF_order_split_diskon]  DEFAULT ((0)) FOR [diskon]
GO
/****** Object:  Default [DF_order_split_kirim]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_split] ADD  CONSTRAINT [DF_order_split_kirim]  DEFAULT ((0)) FOR [kirim]
GO
/****** Object:  Default [DF_order_split_pengajuan_diskon]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_split] ADD  CONSTRAINT [DF_order_split_pengajuan_diskon]  DEFAULT ((0)) FOR [pengajuan_diskon]
GO
/****** Object:  Default [DF_gudang_user_aktif]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[gudang_user] ADD  CONSTRAINT [DF_gudang_user_aktif]  DEFAULT ((1)) FOR [aktif]
GO
/****** Object:  ForeignKey [FK_order_item_split_order_item]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split]  WITH CHECK ADD  CONSTRAINT [FK_order_item_split_order_item] FOREIGN KEY([order_id], [user_id], [item_seq])
REFERENCES [dbo].[order_item] ([order_id], [user_id], [item_seq])
GO
ALTER TABLE [dbo].[order_item_split] CHECK CONSTRAINT [FK_order_item_split_order_item]
GO
/****** Object:  ForeignKey [FK_order_item_split_order_split]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_item_split]  WITH CHECK ADD  CONSTRAINT [FK_order_item_split_order_split] FOREIGN KEY([order_id], [order_id_split], [user_id])
REFERENCES [dbo].[order_split] ([order_id], [order_id_split], [user_id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[order_item_split] CHECK CONSTRAINT [FK_order_item_split_order_split]
GO
/****** Object:  ForeignKey [FK_order_split_order]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[order_split]  WITH CHECK ADD  CONSTRAINT [FK_order_split_order] FOREIGN KEY([order_id], [user_id])
REFERENCES [dbo].[order] ([order_id], [user_id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[order_split] CHECK CONSTRAINT [FK_order_split_order]
GO
/****** Object:  ForeignKey [FK_gudang_user_user]    Script Date: 07/11/2017 15:52:13 ******/
ALTER TABLE [dbo].[gudang_user]  WITH CHECK ADD  CONSTRAINT [FK_gudang_user_user] FOREIGN KEY([user_id])
REFERENCES [dbo].[user] ([user_id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[gudang_user] CHECK CONSTRAINT [FK_gudang_user_user]
GO
